<?php

namespace backend\controllers;

use backend\components\Logger;
use Yii;
use common\models\VietgapStandard;
use common\models\VietgapStandardSearch;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * VietgapStandardController implements the CRUD actions for VietgapStandard model.
 */
class VietgapStandardController extends Controller
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
     * Lists all VietgapStandard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VietgapStandardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $recipes_id = Yii::$app->request->post('editableKey');
            /** @var $model VietgapStandard*/
            $model = VietgapStandard::findOne($recipes_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = Yii::t('app', 'The Vietgap Standard do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            $post = [];
            $posted = current($_POST['VietgapStandard']);
            $post['VietgapStandard'] = $posted;

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
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Vietgap Standard'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Vietgap Standard error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
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
     * Displays a single VietgapStandard model.
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
     * Creates a new VietgapStandard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VietgapStandard();
        $model->scenario = 'adminCreate';

        if ($model->load(Yii::$app->request->post())) {
            if($path = UploadedFile::getInstance($model, 'image')) {
                $ext = $path->extension;
                // generate a unique file name
                $model->image = Yii::$app->security->generateRandomString().".{$ext}";
            }

            $model->active = VietgapStandard::STATUS_ACTIVE;
            if($model->save()) {
                // directory to save image in local
                $dir = Yii::getAlias('@frontend/web/uploads/vietgapstandard/' . $model->id);
                FileHelper::createDirectory($dir);
                $path->saveAs($dir . '/' . $model->image);

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Create Vietgap Standard success'),
                    'title' => Yii::t('app', 'Create Vietgap Standard'),
                ]);

                Logger::log(Logger::INFO, Yii::t('app', 'Create Vietgap Standard success'), Yii::$app->user->identity->email);

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
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Create Vietgap Standard error'),
                    'title' => Yii::t('app', 'Create Vietgap Standard'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Create Vietgap Standard error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save Vietgap Standard.'), Yii::$app->user->identity->email);

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
     * Updates an existing VietgapStandard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($path = UploadedFile::getInstance($model, 'image')) {
                $ext = $path->extension;
                // generate a unique file name
                $model->image = Yii::$app->security->generateRandomString().".{$ext}";
                // directory to save image in local
                $dir = Yii::getAlias('@frontend/web/uploads/vietgapstandard/' . $model->id);
                FileHelper::removeDirectory($dir);
                FileHelper::createDirectory($dir);
                $path->saveAs($dir . '/' . $model->image);
            } else {
                $model->image = $model->getOldAttribute('image');
            }

            $oldModel = $model->oldAttributes;

            if ($model->save()) {

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Update Vietgap Standard success'),
                    'title' => Yii::t('app', 'Update Vietgap Standard'),
                ]);

                Logger::log(Logger::INFO, Yii::t('app', 'Update Vietgap Standard success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);

                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Update Vietgap Standard error'),
                    'title' => Yii::t('app', 'Update Vietgap Standard'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Vietgap Standard error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Vietgap Standard has been edit error.'), Yii::$app->user->identity->email);

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
     * Deletes an existing VietgapStandard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VietgapStandard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VietgapStandard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VietgapStandard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
