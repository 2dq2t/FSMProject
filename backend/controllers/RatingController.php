<?php

namespace backend\controllers;

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
                if($model->validate()) {
                    // can save model or do something before saving model
                    $model->save();
                    $message = '';
                } else {
                    $message = $model->errors;
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

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
                    'message' => Yii::t('app', 'Rating has been saved.'),
                    'title' => Yii::t('app', 'Add Rating'),
                ]);
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
                    'message' => Yii::t('app', 'Could not be save rating. Please try again later.'),
                    'title' => Yii::t('app', 'Add Rating'),
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
     * Updates an existing Rating model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Rating has been edited.'),
                    'title' => Yii::t('app', 'Update Rating'),
                ]);
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Rating has been edited.'),
                    'title' => Yii::t('app', 'Update Rating'),
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
     * Deletes an existing Rating model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('success', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'fa fa-trash-o',
            'message' => Yii::t('app', 'Rating has been deleted.'),
            'title' => Yii::t('app', 'Delete Rating'),
        ]);

        return $this->redirect(['index']);
    }

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
