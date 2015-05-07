<?php

namespace backend\controllers;

use kartik\alert\Alert;
use kartik\helpers\Html;
use Yii;
use common\models\Unit;
use common\models\UnitSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UnitController implements the CRUD actions for Unit model.
 */
class UnitController extends Controller
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
     * Lists all Unit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $unit_id = Yii::$app->request->post('editableKey');
            $model = Unit::findOne($unit_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = 'The Unit do not exist.';
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            // fetch the first entry in posted data (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['Unit']);
            $post['Unit'] = $posted;

            // load model like any single model validation
            if ($model->load($post)) {

                $output = '';
                $message = '';

                if($model->save() && isset($posted['active'])) {
                    if ($posted['active'] == 1) {
                        $label_class = 'label-success';
                        $value = 'Active';
                    } else {
                        $value = 'Inactive';
                        $label_class = 'label-default';
                    }
                    $output = \yii\helpers\Html::tag(
                        'span', Yii::t('app', $value), ['class' => 'label ' . $label_class]
                    );
                } else {
                    $message = $model->validate();
                }

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
     * Displays a single Unit model.
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
     * Creates a new Unit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Unit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 5000,
                'icon' => 'fa fa-plus',
                'message' => 'Unit Record has been saved.',
                'title' => 'Add Unit'
            ]);
            switch (Yii::$app->request->post('action', 'save')) {
                case 'next':
                    return $this->redirect(['create']);
                default:
                    return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Unit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
            Yii::$app->getSession()->setFlash('success', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 5000,
                'icon' => 'fa fa-pencil',
                'message' => 'Unit Record has been updated.',
                'title' => 'Update Unit'
            ]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Unit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('success', [
            'type' => Alert::TYPE_SUCCESS,
            'duration' => 5000,
            'icon' => 'fa fa-trash-o',
            'message' => 'Unit Record has been deleted.',
            'title' => 'Delete Unit'
        ]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Unit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Unit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
