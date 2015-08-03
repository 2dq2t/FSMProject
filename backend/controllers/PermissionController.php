<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\models\AuthItem;
use backend\models\AuthItemSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;
use yii\web\Response;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class PermissionController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
//            'access' => [
//                'class' => \backend\components\AccessControl::className()
//            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch(['type' => Item::TYPE_PERMISSION]);
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());

//        return;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        $model->type = Item::TYPE_PERMISSION;
        if ($model->load(Yii::$app->getRequest()->post())) {
            try {
                if ($model->saveRole()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'Create permission success.'),
                        'title' => Yii::t('app', 'Create Permission')
                    ]);
                    Logger::log(Logger::INFO, Yii::t('app', 'Create permission success'), Yii::$app->user->identity->email);
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
                        'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save permission.'),
                        'title' => Yii::t('app', 'Create Permission')
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Add Permission error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save role.'), Yii::$app->user->identity->email);
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Create Permission')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Add Permission error: ') . $e->getMessage(), Yii::$app->user->identity->email);
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setItem(Yii::$app->getAuthManager()->getPermission($id));
        if ($model->load(Yii::$app->getRequest()->post())) {
            try {
                if ($model->saveRole()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-pencil',
                        'message' => Yii::t('app', 'Update permission success.'),
                        'title' => Yii::t('app', 'Update Permission')
                    ]);
                    Logger::log(Logger::INFO, Yii::t('app', "Update permission '{permission}' success", ['permission' => $id]), Yii::$app->user->identity->email);
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-pencil',
                        'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Update permission error.'),
                        'title' => Yii::t('app', 'Update Permission')
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Permission error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Permission has been edit error.'), Yii::$app->user->identity->email);

                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Update Permission')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Permission error: ') . $e->getMessage(), Yii::$app->user->identity->email);

                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        }

        $model->oldName = $model->name;

        return $this->render('update', ['model' => $model,]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $role = Yii::$app->getAuthManager()->getChildren($id);
//        var_dump($role);return;
        try {
            Yii::$app->getAuthManager()->remove(new Item(['name' => $id]));

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-plus',
                'message' => Yii::t('app', 'Permission_Delete_Success_Msg'),
                'title' => Yii::t('app', 'Delete Permission'),
            ]);
            Logger::log(Logger::INFO, Yii::t('app', 'Permission_Delete_Success_Msg'), Yii::$app->user->identity->email);
        } catch(Exception $ex){
            $errors = [];
            if (!empty(array_filter($children = Yii::$app->getAuthManager()->getChildren($id)))) {
                $errors[] = Yii::t('app', 'Could not delete permission: Permission {permission} has children: ',['permission' => $id]) . " '" . key($children) . "'. " . Yii::t('app', 'Please revoke children before delete permission');
            }

            $model = Yii::$app->db->createCommand('SELECT * FROM auth_assignment WHERE item_name=:item_name');
            $model->bindParam(':item_name', $id);
            $isAssignment = $model->queryOne();

            if (!empty(array_filter($isAssignment))) {
                $errors[] = Yii::t('app', "Could not delete permission: Permission '{permission}' has assigned. ", ['permission' => $id]) . Yii::t('app', 'Please revoke assigned before delete');
            }

            Logger::log(Logger::ERROR, Yii::t('app', 'Delete permission error: ') . empty(array_filter($errors)) ?  Yii::t('app', 'Permission delete error.') : $errors[0], Yii::$app->user->identity->email);
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-trash-o',
                'message' => !empty(array_filter($errors)) ? $errors[0] : Yii::t('app', 'Permission delete error.'),
                'title' => Yii::t('app', 'Delete Permission')
            ]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Assign or remove items
     * @param string $id
     * @param string $action
     * @return array
     */
    public function actionAssign()
    {
        $post = Yii::$app->getRequest()->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];
        $manager = Yii::$app->getAuthManager();
        $parent = $manager->getPermission($id);
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $role) {
                $child = $manager->getPermission($role);
                try {
                    $manager->addChild($parent, $child);
                    Logger::log(Logger::INFO, Yii::t('app', "Assign router '{router}' to permission '{parent}' success", ['router' => $child, 'parent' => $id]), Yii::$app->getUser()->id);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                    Logger::log(Logger::ERROR, Yii::t('app', "Assign router '{router}' to permission '{parent}' error: ", ['router' => $child, 'parent' => $id]) . $exc->getMessage(), Yii::$app->getUser()->id);
                }
            }
        } else {
            foreach ($roles as $role) {
                $child = $manager->getPermission($role);
                try {
                    $manager->removeChild($parent, $child);
                    Logger::log(Logger::INFO, Yii::t('app', "Remove router '{router}' to permission '{parent}' success", ['router' => $child, 'parent' => $id]), Yii::$app->getUser()->id);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                    Logger::log(Logger::ERROR, Yii::t('app', "Remove router '{router}' to permission '{parent}' error: ", ['router' => $child, 'parent' => $id]) . $exc->getMessage(), Yii::$app->getUser()->id);
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
     * Search role
     * @param string $id
     * @param string $target
     * @param string $term
     * @return array
     */
    public function actionSearch($id, $target, $term = '')
    {
        $result = [
            'Permission' => [],
            'Routes' => [],
        ];
        $authManager = Yii::$app->getAuthManager();
        if ($target == 'avaliable') {
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;
            foreach ($authManager->getPermissions() as $name => $role) {
                if (in_array($name, $children)) {
                    continue;
                }
                if (empty($term) or strpos($name, $term) !== false) {
                    $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
                }
            }
        } else {
            foreach ($authManager->getChildren($id) as $name => $child) {
                if (empty($term) or strpos($name, $term) !== false) {
                    $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
                }
            }
        }

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return array_filter($result);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  string        $id
     * @return AuthItem      the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
