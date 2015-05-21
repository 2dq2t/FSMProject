<?php

namespace backend\controllers;

use kartik\alert\Alert;
use Yii;
use backend\models\AuthItem;
use backend\models\AuthItemSearch;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $searchModel = new AuthItemSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $permissions = new ArrayDataProvider(
            [
                'id' => 'permissions',
                'allModels' => Yii::$app->getAuthManager()->getPermissions(),
                'sort' => [
                    'attributes' => ['name', 'description', 'rule_name', 'createdAt', 'updatedAt'],
                ],
                'pagination' => [
                    'pageSize' => 7,
                ],
            ]
        );

        $roles = new ArrayDataProvider(
            [
                'id' => 'roles',
                'allModels' => Yii::$app->getAuthManager()->getRoles(),
                'sort' => [
                    'attributes' => ['name', 'description', 'rule_name', 'createdAt', 'updatedAt'],
                ],
                'pagination' => [
                    'pageSize' => 7,
                ],
            ]
        );

        return $this->render('index', [
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $rules = ArrayHelper::map(\Yii::$app->getAuthManager()->getRules(), 'name', 'name');
        $model = new AuthItem();

        // get all auth_item
        $items = ArrayHelper::map(Yii::$app->getAuthManager()->getPermissions(),
            'name',
            function ($item) {
                return $item->name.(strlen($item->description) > 0 ? ' ['.$item->description.']' : '');
            }
        );

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->createItem();
            if (strlen($model->getErrorMessage()) > 0) {

                Yii::$app->getSession()->setFlash('error', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', $model->getErrorMessage()),
                    'title' => Yii::t('app', 'Add Permission'),
                ]);
                return $this->render('create', [
                    'model' => $model,
                    'rules' => $rules,
                    'items' => $items
                ]);
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Permission has been saved.'),
                    'title' => Yii::t('app', 'Add Permission'),
                ]);

                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->redirect(['create']);
                    default:
                        return $this->redirect(['index']);
                }
            }

        } else {
            $model->type = Item::TYPE_PERMISSION;

            return $this->render('create', [
                'model' => $model,
                'rules' => $rules,
                'items' => $items,
            ]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $rules = ArrayHelper::map(\Yii::$app->getAuthManager()->getRules(), 'name', 'name');
        $model = $this->findModel($id);
        $items = ArrayHelper::map(Yii::$app->getAuthManager()->getPermissions(),
            'name',
            function ($item) {
                return $item->name.(strlen($item->description) > 0 ? ' ['.$item->description.']' : '');
            }
        );
        $children = Yii::$app->getAuthManager()->getChildren($id);

        foreach ($children as $child) {
            $model->children[] = $child->name;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->updateItem();

            if (strlen($model->getErrorMessage()) > 0) {

                Yii::$app->getSession()->setFlash('error', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', $model->getErrorMessage()),
                    'title' => Yii::t('app', 'Edit Permission'),
                ]);
                return $this->render('update', [
                    'model' => $model,
                    'rules' => $rules,
                    'items' => $items,
                ]);
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Permission has been edited.'),
                    'title' => Yii::t('app', 'Edit Permission'),
                ]);

                return $this->redirect(['index']);
            }

        } else {

            $model->oldname = $model->name;

            return $this->render('update', [
                'model' => $model,
                'rules' => $rules,
                'items' => $items,
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        Yii::$app->getAuthManager()->remove(new Item(['name' => $id]));

        Yii::$app->getSession()->setFlash('success', [
            'type' => Alert::TYPE_SUCCESS,
            'duration' => 3000,
            'icon' => 'fa fa-plus',
            'message' => Yii::t('app', 'Permission has been deleted.'),
            'title' => Yii::t('app', 'Delete Permission'),
        ]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
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
