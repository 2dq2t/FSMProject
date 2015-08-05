<?php

namespace backend\controllers;

use backend\components\Logger;
use Yii;
use common\models\Guest;
use common\models\GuestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GuestController implements the CRUD actions for Guest model.
 */
class GuestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-all' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all Guest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GuestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Guest model.
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
     * Creates a new Guest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Guest();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Logger::log(Logger::INFO, Yii::t('app', 'Add Guest success'), Yii::$app->user->identity->email);
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Guest_Add_Success_Msg'),
                    'title' => Yii::t('app', 'Create Guest')
                ]);
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->redirect(['create']);
                    default:
                        return $this->redirect(['index']);
                }
            } else {
                Logger::log(Logger::ERROR, Yii::t('app', 'Add Guest error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Guest Record saved error.'), Yii::$app->user->identity->email);
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => current($model->getFirstErrors()) ? $model->getFirstErrors() : Yii::t('app', 'Guest_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create Guest')
                ]);
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Guest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $oldModel = $model->oldAttributes;
            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Guest_Update_Success_Msg'),
                    'title' => Yii::t('app', 'Update Guest')
                ]);

                Logger::log(Logger::INFO, Yii::t('app', 'Update Guest success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                return $this->redirect(['index']);
            } else {
                Logger::log(Logger::ERROR, Yii::t('app', 'Update Guest error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Guest has been edit error.'), Yii::$app->user->identity->email);
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Guest_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update Guest')
                ]);

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Guest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();

//        Yii::$app->getSession()->setFlash('success', [
//            'type' => 'success',
//            'duration' => 3000,
//            'icon' => 'fa fa-trash-o',
//            'message' => Yii::t('app', 'Guest_Delete_Success_Msg'),
//            'title' => Yii::t('app', 'Delete Guest')
//        ]);
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Guest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Guest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Guest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
