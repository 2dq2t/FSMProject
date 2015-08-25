<?php

namespace backend\controllers;

use backend\components\Logger;
use kartik\helpers\Html;
use Yii;
use common\models\Faq;
use common\models\FaqSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller
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
     * Lists all Faq models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $faq_id = Yii::$app->request->post('editableKey');
            $model = Faq::findOne($faq_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = Yii::t('app', 'The Faq do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            // fetch the first entry in posted file (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted file for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['Faq']);
            $post['Faq'] = $posted;

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
                    Logger::log(Logger::INFO, Yii::t('app', 'Update FAQs success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update FAQs error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
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
     * Displays a single Faq model.
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
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Faq();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'FAQs_Add_Success_Msg'),
                    'title' => Yii::t('app', 'Create FAQs')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Add FAQs success'), Yii::$app->user->identity->email);
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->redirect(['create']);
                    default:
                        return $this->redirect(['index']);
                }
            } else {
                Logger::log(Logger::ERROR, Yii::t('app', 'Add FAQs error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'FAQs Record saved error.'), Yii::$app->user->identity->email);
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) :  Yii::t('app', 'FAQs_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create FAQs')
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
     * Updates an existing Faq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
                    'message' => Yii::t('app', 'FAQs_Update_Success_Msg'),
                    'title' => Yii::t('app', 'Update FAQs')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Update FAQs success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                return $this->redirect(['index']);
            } else {
                Logger::log(Logger::ERROR, Yii::t('app', 'Update Category error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'FAQs has been edit error.'), Yii::$app->user->identity->email);
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'FAQs_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update FAQs')
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
     * Deletes an existing Faq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $faq = $this->findModel($id);
//        $faq->active = Faq::STATUS_INACTIVE;
//        if ($faq->save()) {
//            Logger::log(Logger::INFO, Yii::t('app', 'Delete faqs success'), Yii::$app->user->identity->email);
//            Yii::$app->getSession()->setFlash('success', [
//                'type' => 'success',
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => Yii::t('app', 'FAQs_Delete_Success_Msg'),
//                'title' => Yii::t('app', 'Delete FAQs')
//            ]);
//        } else {
//            Logger::log(Logger::ERROR, Yii::t('app', 'Delete faqs error: ') . current($faq->getFirstErrors()) ? current($faq->getFirstErrors()) : Yii::t('app', 'Faqs delete error.'), Yii::$app->user->identity->email);
//            Yii::$app->getSession()->setFlash('error', [
//                'type' => 'error',
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => Yii::t('app', 'Could not delete this FAQs. Please try again.'),
//                'title' => Yii::t('app', 'Delete FAQs')
//            ]);
//        }
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
