<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\models\AuthItem;
use backend\models\AuthItemSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RoleController extends Controller
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
            'access' => [
                'class' => YII_DEBUG ?  \yii\base\ActionFilter::className() : \backend\components\AccessControl::className()
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $searchModel->type = Item::TYPE_ROLE;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param  string $id
     * @return mixed
     */
//    public function actionView($id)
//    {
//        $model = $this->findModel($id);
//        return $this->render('view', ['model' => $model]);
//    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        $model->type = Item::TYPE_ROLE;
        $result = [
            'Roles' => [],
            'Permissions' => [],
            'Routes' => [],
        ];

        $authManager = Yii::$app->authManager;
        foreach ($authManager->getRoles() as $name => $role) {
            if (empty($term) or strpos($name, $term) !== false) {
                $result['Roles'][$name] = $name;
            }
        }
        foreach ($authManager->getPermissions() as $name => $role) {
            if (empty($term) or strpos($name, $term) !== false) {
                $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
            }
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->createItem()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'Create role success.'),
                        'title' => Yii::t('app', 'Create Role')
                    ]);
                    Logger::log(Logger::INFO, Yii::t('app', 'Create role success.'), Yii::$app->user->identity->email);
                    $transaction->commit();
                    switch (Yii::$app->request->post('action', 'save')) {
                        case 'next':
                            return $this->redirect(['create']);
                        default:
                            return $this->redirect(['index']);
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', $model->getErrorMessage()),//current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save role.'),
                        'title' => Yii::t('app', 'Create Role')
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Add Permission error: ') . Yii::t('app', $model->getErrorMessage()), Yii::$app->user->identity->email);

                    return $this->render('create', [
                        'model' => $model,
                        'item' => $result
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Create Role')
                ]);
                Logger::log(Logger::ERROR, Yii::t('app', 'Add Role error: ') . $e->getMessage(), Yii::$app->user->identity->email);
                return $this->render('create', [
                    'model' => $model,
                    'item' => $result
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'item' => $result
            ]);
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
        $model->setItems(array_keys(Yii::$app->authManager->getChildren($id)));

        $result = [
            'Roles' => [],
            'Permissions' => [],
            'Routes' => [],
        ];
        $authManager = Yii::$app->authManager;

        foreach ($authManager->getRoles() as $name => $role) {
            if ($name === $id) {
                continue;
            }
            if (empty($term) or strpos($name, $term) !== false) {
                $result['Roles'][$name] = $name;
            }
        }
        foreach ($authManager->getPermissions() as $name => $role) {
            if ($name === $id) {
                continue;
            }
            if (empty($term) or strpos($name, $term) !== false) {
                $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!isset(Yii::$app->request->post('AuthItem')['items'])) {
                $model->setItems([]);
            }

            $transaction = Yii::$app->db->beginTransaction();
            Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();
            try {
                if ($model->updateItem()) {
                    Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-pencil',
                        'message' => Yii::t('app', 'Update role success.'),
                        'title' => Yii::t('app', 'Update Role')
                    ]);
                    Logger::log(Logger::INFO, Yii::t('app', "Update role '{role}' success", ['role' => $id]), Yii::$app->user->identity->email);
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-pencil',
                        'message' => $model->getErrorMessage(),//current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Update role error.'),
                        'title' => Yii::t('app', 'Update Role')
                    ]);

                        Logger::log(Logger::ERROR, Yii::t('app', 'Update Role error: ') . $model->getErrorMessage(), Yii::$app->user->identity->email);

                    return $this->render('update', [
                        'model' => $model,
                        'item' => $result
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Update Role')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Role error: ') . $e->getMessage(), Yii::$app->user->identity->email);

                return $this->render('update', [
                    'model' => $model,
                    'item' => $result
                ]);
            }
        }

        $model->setOldName($model->name);

        return $this->render('update', [
            'model' => $model,
            'item' => $result
        ]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $transaction = Yii::$app->db->beginTransaction();
//        try {
//            $model = $this->findModel($id);
//            Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();
//            if ($model->deleteItem()) {
//                Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
//                $transaction->commit();
//
//                Yii::$app->getSession()->setFlash('success', [
//                    'type' => 'success',
//                    'duration' => 3000,
//                    'icon' => 'fa fa-plus',
//                    'message' => Yii::t('app', 'Delete Role success'),
//                    'title' => Yii::t('app', 'Delete Role'),
//                ]);
//                Logger::log(Logger::INFO, Yii::t('app', 'Delete Role success'), Yii::$app->user->identity->email);
//
//            } else {
//                Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
//                if ($transaction->isActive) {
//                    $transaction->rollBack();
//                }
//
//                Logger::log(Logger::ERROR, Yii::t('app', 'Delete role error: ') . $model->getErrorMessage() ? $model->getErrorMessage() : Yii::t('app', 'Role delete error.'), Yii::$app->user->identity->email);
//                Yii::$app->getSession()->setFlash('error', [
//                    'type' => 'error',
//                    'duration' => 0,
//                    'icon' => 'fa fa-trash-o',
//                    'message' => $model->getErrorMessage() ? $model->getErrorMessage() : Yii::t('app', 'Role delete error.'),
//                    'title' => Yii::t('app', 'Delete Role')
//                ]);
//            }
//
//        } catch(Exception $ex){
//            if ($transaction->isActive) {
//                $transaction->rollBack();
//            }
//            Logger::log(Logger::ERROR, Yii::t('app', 'Delete role error: ') . $ex->getMessage(), Yii::$app->user->identity->email);
//            Yii::$app->getSession()->setFlash('error', [
//                'type' => 'error',
//                'duration' => 0,
//                'icon' => 'fa fa-trash-o',
//                'message' => $ex->getMessage(),
//                'title' => Yii::t('app', 'Delete Role')
//            ]);
//        }
//
//        return $this->redirect(['index']);
//    }

    /**
     * Assign or remove items
     * @param string $id
     * @param string $action
     * @return array
     */
//    public function actionAssign()
//    {
//        $post = Yii::$app->getRequest()->post();
//        $id = $post['id'];
//        $action = $post['action'];
//        $roles = $post['roles'];
//        $manager = Yii::$app->getAuthManager();
//        $parent = $manager->getRole($id);
//        $error = [];
//        if ($action == 'assign') {
//            foreach ($roles as $role) {
//                $child = $manager->getRole($role);
//                $child = $child ? : $manager->getPermission($role);
//                try {
//                    $manager->addChild($parent, $child);
//                    Logger::log(Logger::INFO, Yii::t('app', "Assign router '{router}' to role '{parent}' success", ['router' => $child, 'parent' => $id]), Yii::$app->user->identity->email);
//                } catch (\Exception $e) {
//                    $error[] = $e->getMessage();
//                }
//            }
//        } else {
//            foreach ($roles as $role) {
//                $child = $manager->getRole($role);
//                $child = $child ? : $manager->getPermission($role);
//                try {
//                    $manager->removeChild($parent, $child);
//                } catch (\Exception $e) {
//                    $error[] = $e->getMessage();
//                }
//            }
//        }
//        Yii::$app->response->format = 'json';
//
//        return[
//            'type' => 'S',
//            'errors' => $error,
//        ];
//    }

    /**
     * Search role
     * @param string $id
     * @param string $target
     * @param string $term
     * @return array
     */
//    public function actionSearch($id, $target, $term = '')
//    {
//        $result = [
//            'Roles' => [],
//            'Permissions' => [],
//            'Routes' => [],
//        ];
//        $authManager = Yii::$app->authManager;
//        if ($target == 'avaliable') {
//            $children = array_keys($authManager->getChildren($id));
//            $children[] = $id;
//            foreach ($authManager->getRoles() as $name => $role) {
//                if (in_array($name, $children)) {
//                    continue;
//                }
//                if (empty($term) or strpos($name, $term) !== false) {
//                    $result['Roles'][$name] = $name;
//                }
//            }
//            foreach ($authManager->getPermissions() as $name => $role) {
//                if (in_array($name, $children)) {
//                    continue;
//                }
//                if (empty($term) or strpos($name, $term) !== false) {
//                    $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
//                }
//            }
//        } else {
//            foreach ($authManager->getChildren($id) as $name => $child) {
//                if (empty($term) or strpos($name, $term) !== false) {
//                    if ($child->type == Item::TYPE_ROLE) {
//                        $result['Roles'][$name] = $name;
//                    } else {
//                        $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
//                    }
//                }
//            }
//        }
//        Yii::$app->response->format = 'json';
//
//        return array_filter($result);
//    }

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