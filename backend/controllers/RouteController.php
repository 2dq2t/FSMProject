<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\components\RouteHelper;
use backend\models\AuthItem;
use Yii;
use yii\rbac\Item;
use yii\web\Response;

/**
 * Description of RuleController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RouteController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => YII_DEBUG ?  \yii\base\ActionFilter::className() : \backend\components\AccessControl::className()
            ],
        ];
    }
    /**
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        $manager = Yii::$app->getAuthManager();
        $exists = $routes = [];
        foreach (RouteHelper::getAppRoutes() as $route) {
            $routes[$route] = $route;
        }
        foreach ($manager->getPermissions() as $name => $permission) {
            if ($name[0] !== '/') {
                continue;
            }
            $exists[$name] = $name;
            if (isset($routes[$name])) {
                unset($routes[$name]);
            }
        }
        return $this->render('index', [
            'new' => $routes,
            'exists' => $exists
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        if ($model->load(Yii::$app->getRequest()->post())) {
            $model->type = Item::TYPE_PERMISSION;
            if ($model->validate()) {
//                $routes = preg_split('/\s*,\s*/', trim($model->name), -1, PREG_SPLIT_NO_EMPTY);

                $model->name = preg_replace('/^[^a-zA-Z]+/', '/', $model->name);
                if ($model->createItem()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'Create route success.'),
                        'title' => Yii::t('app', 'Create Route')
                    ]);
                    Logger::log(Logger::INFO, Yii::t('app', 'Create route success'), Yii::$app->user->identity->email);
                    switch (Yii::$app->request->post('action', 'save')) {
                        case 'next':
                            return $this->redirect(['create']);
                        default:
                            return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', $model->getErrorMessage()),//current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save role.'),
                        'title' => Yii::t('app', 'Create Route')
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Add Route error: ') . Yii::t('app', $model->getErrorMessage()), Yii::$app->user->identity->email);

                    return $this->render('create', [
                        'model' => $model
                    ]);
                }
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Assign or remove items
     * @return array
     */
    public function actionAssign()
    {
        $post = Yii::$app->getRequest()->post();
        $action = $post['action'];
        $routes = $post['routes'];

        $model = new AuthItem();
        $model->type = Item::TYPE_PERMISSION;
        $error = [];
        $transaction = Yii::$app->db->beginTransaction();
        Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();
        if ($action == 'assign') {
            foreach ($routes as $route) {
                $model->name = $route;
                if (!$model->createItem()) $error[] = $model->getErrorMessage();
            }

            if (empty($error)) {
                Logger::log(Logger::INFO, Yii::t('app', 'Assign route success'), Yii::$app->user->identity->email);
            } else {
                Logger::log(Logger::ERROR, Yii::t('app', 'Assign route error: ') . $error[0], Yii::$app->user->identity->email);
            }
        } else {
            foreach ($routes as $route) {
                $model->name = $route;
                if (!$model->deleteItem()) $error[] = $model->getErrorMessage();
            }
            if (empty($error)) {
                Logger::log(Logger::INFO, Yii::t('app', 'Delete route success'), Yii::$app->user->identity->email);
            } else {
                Logger::log(Logger::ERROR, Yii::t('app', 'Delete route error: ') . $error[0], Yii::$app->user->identity->email);
            }
        }

        Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
        if (empty($error)) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return[
            'errors' => $error,
        ];
    }

    /**
     * Search Route
     * @param string $target
     * @return array
     */
    public function actionGetRoutes($target)
    {
        $results = [];
        $manager = Yii::$app->getAuthManager();

        $exists = array_keys($manager->getPermissions());
        $routes = RouteHelper::getAppRoutes();
        if ($target == 'available') {
            foreach ($routes as $route) {
                if (in_array($route, $exists)) {
                    continue;
                }
                $results[$route] = true;
            }
        } else {
            foreach ($exists as $name) {
                if ($name[0] !== '/') {
                    continue;
                }
                $results[$name] = true;
            }
        }

        Yii::$app->response->format = 'json';
        return $results;
    }
}
