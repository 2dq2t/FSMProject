<?php

namespace backend\controllers;

use backend\components\Logger;
use Yii;
use backend\models\Route;
use yii\caching\TagDependency;
use yii\web\Response;
use backend\components\Configs;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use Exception;

/**
 * Description of RuleController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RouteController extends \yii\web\Controller
{
    /**
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        return $this->render('index');
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Route;
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($model->validate()) {
                $routes = preg_split('/\s*,\s*/', trim($model->route), -1, PREG_SPLIT_NO_EMPTY);
                $this->saveNew($routes);
                $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Assign or remove items
     * @param string $action
     * @return array
     */
    public function actionAssign()
    {
        $post = Yii::$app->getRequest()->post();
        $action = $post['action'];
        $routes = $post['routes'];
        $manager = Yii::$app->getAuthManager();

        $error = [];
        if ($action == 'assign') {
            $this->saveNew($routes);
        } else {
            foreach ($routes as $route) {
                $child = $manager->getPermission($route);
                try {
                    $manager->remove($child);
                } catch (Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return[
            'type' => 'S',
            'errors' => $error,
        ];
    }

    /**
     * Save one or more route(s)
     * @param array $routes
     */
    private function saveNew($routes)
    {
        $manager = Yii::$app->getAuthManager();
        foreach ($routes as $route) {
            try {
                $item = $manager->createPermission('/' . trim($route, '/'));
                $manager->add($item);
            } catch (Exception $e) {

            }
        }
    }

    /**
     * Search Route
     * @param string $target
     * @param string $term
     * @param string $refresh
     * @return array
     */
    public function actionSearch($target, $term = '')
    {
        $results = [];
        $manager = Yii::$app->getAuthManager();

        $exists = array_keys($manager->getPermissions());
        $result = [];
        $this->getRouteRecursive(Yii::$app, $result);

        $routes = $result;
        if ($target == 'avaliable') {
            foreach ($routes as $route) {
                if (in_array($route, $exists)) {
                    continue;
                }
                if (empty($term) or strpos($route, $term) !== false) {
                    $results[$route] = true;
                }
            }
        } else {
            foreach ($exists as $name) {
                if ($name[0] !== '/') {
                    continue;
                }
                if (empty($term) or strpos($name, $term) !== false) {
                    $r = explode('&', $name);
                    $results[$name] = !empty($r[0]) && in_array($r[0], $routes);
                }
            }
        }

        Yii::$app->response->format = 'json';
        return $results;
    }

    /**
     * Get route(s) recursive
     * @param \yii\base\Module $module
     * @param array $result
     */
    private function getRouteRecursive($module, &$result)
    {
        try {
            foreach ($module->getModules() as $id => $child) {
                if (($child = $module->getModule($id)) !== null) {
                    $this->getRouteRecursive($child, $result);
                }
            }

            foreach ($module->module->controllerMap as $id => $type) {
                $this->getControllerActions($type, $id, $module, $result);
            }

            $namespace = trim($module->module->controllerNamespace, '\\') . '\\';
            $this->getControllerFiles($module, $namespace, '', $result);
            $result[] = ($module->uniqueId === '' ? '' : '/' . $module->uniqueId) . '/*';
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
    }

    /**
     * Get list controller under module
     * @param \yii\base\Module $module
     * @param string $namespace
     * @param string $prefix
     * @param mixed $result
     * @return mixed
     */
    private function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = @Yii::getAlias('@backend' . str_replace('\\', '/', $namespace));
        $result[] = $path;
        try {
            if (!is_dir($path)) {
                return;
            }
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($path . '/' . $file)) {
                    $this->getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                    $id = Inflector::camel2id(substr(basename($file), 0, -14));
                    $className = $namespace . Inflector::id2camel($id) . 'Controller';
                    if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                        $this->getControllerActions($className, $prefix . $id, $module, $result);
                    }
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
    }

    /**
     * Get list action of controller
     * @param mixed $type
     * @param string $id
     * @param \yii\base\Module $module
     * @param string $result
     */
    private function getControllerActions($type, $id, $module, &$result)
    {
        try {
            /* @var $controller \yii\base\Controller */
            $controller = Yii::createObject($type, [$id, $module->module]);
            $this->getActionRoutes($controller, $result);
            $result[] = '/' . $controller->uniqueId . '/*';
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
    }

    /**
     * Get route of action
     * @param \yii\base\Controller $controller
     * @param array $result all controller action.
     */
    private function getActionRoutes($controller, &$result)
    {
        try {
            $prefix = '/' . $controller->uniqueId . '/';
            foreach ($controller->actions() as $id => $value) {
                $result[] = $prefix . $id;
            }
            $class = new \ReflectionClass($controller);
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $result[] = $prefix . Inflector::camel2id(substr($name, 6));
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
    }
}
