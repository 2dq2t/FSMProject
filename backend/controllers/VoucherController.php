<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\components\ParserDateTime;
use kartik\alert\Alert;
use Yii;
use common\models\Voucher;
use common\models\VoucherSearch;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VoucherController implements the CRUD actions for Voucher model.
 */
class VoucherController extends Controller
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
     * Lists all Voucher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VoucherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $voucher_id = Yii::$app->request->post('editableKey');
            $model = Voucher::findOne($voucher_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = Yii::t('app', 'The Product do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            // fetch the first entry in posted file (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted file for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['Voucher']);
            $post['Voucher'] = $posted;

            // load model like any single model validation
            if ($model->load($post)) {

                $output = '';
                $message = '';
                $oldModel = $model->oldAttributes;

                if($model->save() && isset($posted['active'])) {
                    if ($posted['active'] == 1) {
                        $label_class = 'label-success';
                        $value = 'Active';
                    } else {
                        $value = 'Inactive';
                        $label_class = 'label-default';
                    }
                    $output = Html::tag(
                        'span', Yii::t('app', $value), ['class' => 'label ' . $label_class]
                    );
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Voucher success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Voucher error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
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
     * Displays a single Voucher model.
     * @param string $id
     * @return mixed
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new Voucher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Voucher();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->start_date = ParserDateTime::parseToTimestamp($model->start_date);
            $model->end_date = ParserDateTime::parseToTimestamp($model->end_date);

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Voucher_Add_Success_Msg'),
                    'title' => Yii::t('app', 'Create Voucher')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Add Voucher success'), Yii::$app->user->identity->email);
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->redirect(['create']);
                    default:
                        return $this->redirect(['index']);
                }
            } else {
                if ($model->start_date != '') {
                    $model->start_date = date('d/m/Y', $model->start_date);
                }

                if($model->end_date != '') {
                    $model->end_date = date('d/m/Y', $model->end_date);
                }

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Voucher_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create Voucher')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Add Voucher error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Voucher_Add_Error_Msg'), Yii::$app->user->identity->email);
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        } else {
//            if ($model->start_date != '') {
//                $model->start_date = date('d/m/Y', $model->start_date);
//            }
//
//            if($model->end_date != '') {
//                $model->end_date = date('d/m/Y', $model->end_date);
//            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Voucher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->start_date = date('d/m/Y', $model->start_date);
        $model->end_date = date('d/m/Y', $model->end_date);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $oldModel = $model->oldAttributes;
            $model->start_date = ParserDateTime::parseToTimestamp($model->start_date);
            $model->end_date = ParserDateTime::parseToTimestamp($model->end_date);

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Voucher_Update_Success_Msg'),
                    'title' => Yii::t('app', 'Update Voucher')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Update Voucher success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                return $this->redirect(['index']);
            } else {
                if ($model->start_date != '') {
                    $model->start_date = date('d/m/Y', $model->start_date);
                }

                if($model->end_date != '') {
                    $model->end_date = date('d/m/Y', $model->end_date);
                }

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Voucher_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update Voucher')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Voucher error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Voucher_Update_Error_Msg'), Yii::$app->user->identity->email);

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
     * Deletes an existing Voucher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $voucher = $this->findModel($id);
//        $voucher->active = Voucher::STATUS_INACTIVE;
//        if ($voucher->save()) {
//            Yii::$app->getSession()->setFlash('success', [
//                'type' => 'success',
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => Yii::t('app', 'Voucher_Delete_Success_Msg'),
//                'title' => Yii::t('app', 'Delete Voucher')
//            ]);
//            Logger::log(Logger::INFO, Yii::t('app', 'Voucher_Delete_Success_Msg'), Yii::$app->user->identity->email);
//        } else {
//            Yii::$app->getSession()->setFlash('error', [
//                'type' => 'error',
//                'duration' => 0,
//                'icon' => 'fa fa-trash-o',
//                'message' => current($voucher->getFirstErrors()) ? current($voucher->getFirstErrors()) : Yii::t('app', 'Could not delete this voucher. Please try again.'),
//                'title' => Yii::t('app', 'Delete Voucher')
//            ]);
//            Logger::log(Logger::ERROR, Yii::t('app', 'Delete Voucher error: ') . current($voucher->getFirstErrors()) ? current($voucher->getFirstErrors()) : Yii::t('app', 'Voucher delete error.'), Yii::$app->user->identity->email);
//        }
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Voucher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Voucher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Voucher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
