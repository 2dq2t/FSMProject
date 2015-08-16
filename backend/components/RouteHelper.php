<?php

namespace backend\components;

use ReflectionClass;
use yii\caching\TagDependency;
use yii\helpers\Inflector;
use \Yii;
use yii\web\Controller;

/**
 * Class RouteHelper
 * @package yii2mod\rbac\components
 */
class RouteHelper
{
    /**
     * Tag - file, for invalidate tag dependency cache
     */
    const ACCESS_CACHE = 'access';
    /**
     * Get list of application routes
     * @param $refresh boolean
     * @return array
     */
    public static function getAppRoutes($refresh = false)
    {
        $key = __METHOD__;
        if ($refresh) self::refreshFileCache();
        if (($cache = Yii::$app->getCache()) === null || ($result = $cache->get($key)) === false) {
            $result = [];
            self::getRouteRecursive(Yii::$app, $result);
//            /* @var $cache Cache*/
            if ($cache !== null) {
                $cache->set($key, $result, 0, new TagDependency([
                    'tags' => static::ACCESS_CACHE
                ]));
            }
        }

        return $result;
    }

    /**
     * Get route(s) recursive
     * @param \yii\base\Module $module
     * @param array $result
     */
    private static function getRouteRecursive($module, &$result)
    {
        try {
            foreach ($module->getModules() as $id => $child) {
                if (($child = $module->getModule($id)) !== null) {
                    self::getRouteRecursive($child, $result);
                }
            }

            foreach ($module->module->controllerMap as $id => $type) {
                self::getControllerActions($type, $id, $module->module, $result);
            }

            $namespace = trim($module->module->controllerNamespace, '\\') . '\\';
            self::getControllerFiles($module->module, $namespace, '', $result);
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
    private static function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = @Yii::getAlias('@backend' . str_replace('\\', '/', $namespace));
        try {
            if (!is_dir($path)) {
                return;
            }
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($path . '/' . $file)) {
                    self::getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                    $id = Inflector::camel2id(substr(basename($file), 0, -14));
                    $className = $namespace . Inflector::id2camel($id) . 'Controller';
                    if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                        /* @var $controller Controller*/
                        $controller = Yii::createObject($className, [
                            $prefix . $id,
                            $module
                        ]);
                        self::getActionRoutes($controller, $result);
                        $result[] = '/' . $controller->uniqueId . '/*';
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
    private static function getControllerActions($type, $id, $module, &$result)
    {
        try {
            /* @var $controller \yii\base\Controller */
            $controller = Yii::createObject($type, [$id, $module]);
            self::getActionRoutes($controller, $result);
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
    private static function getActionRoutes($controller, &$result)
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

    /**
     * Refresh file cache
     * @static
     */
    private static function refreshFileCache()
    {
        if (($cache = Yii::$app->getCache()) !== null) {
            TagDependency::invalidate($cache, self::ACCESS_CACHE);
        }
    }
}