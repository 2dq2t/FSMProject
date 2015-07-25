<?php

namespace backend\controllers;

use backend\components\Logger;
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
                    'delete-all' => ['post']
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
                $message = Yii::t('app', 'The Unit do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            // fetch the first entry in posted file (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted file for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['Unit']);
            $post['Unit'] = $posted;
            $oldModel = $model->oldAttributes;

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
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Unit'), Yii::$app->getUser()->id, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Unit error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->getUser()->id);
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

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Unit_Add_Success_Msg'),
                    'title' => Yii::t('app', 'Create Unit')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Add Unit success'), Yii::$app->getUser()->id);
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
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Unit_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create Unit')
                ]);
                Logger::log(Logger::ERROR, Yii::t('app', 'Add Unit error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Unit_Add_Error_Msg'), Yii::$app->getUser()->id);
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
     * Updates an existing Unit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $oldModel = $model->oldAttributes;
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Unit_Update_Success_Msg'),
                    'title' => Yii::t('app', 'Update Unit')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Unit_Update_Success_Msg'), Yii::$app->getUser()->id, $oldModel, $model->attributes);
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Unit_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update Unit')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Unit error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Unit_Update_Error_Msg'), Yii::$app->getUser()->id);

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
     * Deletes an existing Unit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $unit = $this->findModel($id);
        $unit->active = Unit::STATUS_INACTIVE;
        if ($unit->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-trash-o',
                'message' => Yii::t('app', 'Unit_Delete_Success_Msg'),
                'title' => Yii::t('app', 'Delete Unit')
            ]);
            Logger::log(Logger::INFO, Yii::t('app', 'Unit_Delete_Success_Msg'), Yii::$app->getUser()->id);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-trash-o',
                'message' => current($unit->getFirstErrors()) ? current($unit->getFirstErrors()) : Yii::t('app', 'Could not delete this unit. Please try again'),
                'title' => Yii::t('app', 'Delete Unit')
            ]);

            Logger::log(Logger::ERROR, Yii::t('app', 'Delete unit error: ') . current($unit->getFirstErrors()) ? current($unit->getFirstErrors()) : Yii::t('app', 'Unit delete error.'), Yii::$app->getUser()->id);
        }

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
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
