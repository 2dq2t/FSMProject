<?php

namespace backend\controllers;

use backend\components\Logger;
use Yii;
use common\models\Rating;
use common\models\RatingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * RatingController implements the CRUD actions for Rating model.
 */
class RatingController extends Controller
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
            'access' => [
                'class' => YII_DEBUG ?  \yii\base\ActionFilter::className() : \backend\components\AccessControl::className()
            ],
        ];
    }

    /**
     * Lists all Rating models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RatingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $rating_id = Yii::$app->request->post('editableKey');
            $model = Rating::findOne($rating_id);

            // store a default json response as desired by editable
            $out = Json::encode(['output'=>'', 'message'=>'']);

            $post = [];
            $posted = current($_POST['Rating']);
            $post['Rating'] = $posted;

            // load model like any single model validation
            if ($model->load($post)) {
                $oldModel = $model->oldAttributes;
                if($model->save()) {
                    $message = '';
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Rating'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Rating error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
                }
                $output = '';

                $out = Json::encode(['output'=>$output, 'message'=>$message]);
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rating model.
     * @param integer $id
     * @return mixed
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new Rating model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rating();

        if ($model->load(Yii::$app->request->post()) ) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Rating_Add_Success_Msg'),
                    'title' => Yii::t('app', 'Create Rating'),
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Add Rating success'), Yii::$app->user->identity->email);
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
                    'message' => Yii::t('app', 'Rating_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create Rating'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Add Rating error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save rating.'), Yii::$app->user->identity->email);

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
     * Updates an existing Rating model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $oldModel = $model->oldAttributes;
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Rating_Update_Success_Msg'),
                    'title' => Yii::t('app', 'Update Rating'),
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Update Rating success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Rating_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update Rating'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Rating error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Rating has been edit error.'), Yii::$app->user->identity->email);

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
     * Deletes an existing Rating model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        Logger::log(Logger::INFO, Yii::t('app', 'Delete rating success'), Yii::$app->user->identity->email);
//
//        Yii::$app->getSession()->setFlash('success', [
//            'type' => 'success',
//            'duration' => 5000,
//            'icon' => 'fa fa-trash-o',
//            'message' => Yii::t('app', 'Rating_Delete_Success_Msg'),
//            'title' => Yii::t('app', 'Delete Rating'),
//        ]);
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Rating model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rating the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rating::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
