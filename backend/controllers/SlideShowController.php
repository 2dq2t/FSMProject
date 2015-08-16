<?php

namespace backend\controllers;

use backend\components\Logger;
use Yii;
use common\models\SlideShow;
use common\models\SlideShowSearch;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SlideShowController implements the CRUD actions for SlideShow model.
 */
class SlideShowController extends Controller
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
     * Lists all SlideShow models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlideShowSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $slideshow_id = Yii::$app->request->post('editableKey');
            $model = SlideShow::findOne($slideshow_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = Yii::t('app', 'The Slideshow do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            $post = [];
            $posted = current($_POST['SlideShow']);
            $post['SlideShow'] = $posted;

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
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Slide show'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Slide show error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
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
     * Displays a single SlideShow model.
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
     * Creates a new SlideShow model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SlideShow();
        $model->scenario = 'adminCreate';

        $path = UploadedFile::getInstance($model, 'image');

        if($path) {
            $ext = $path->extension;
            // generate a unique file name
            $model->path = Yii::$app->security->generateRandomString().".{$ext}";
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->image = $model->path;
            if($model->save()) {
                // directory to save image in local
                $dir = Yii::getAlias('@frontend/web/uploads/slideshow/' . $model->id);
                FileHelper::createDirectory($dir);
                $path->saveAs($dir . '/' . $model->path);

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'SlideShow_Add_Success_Msg'),
                    'title' => Yii::t('app', 'Create SlideShow'),
                ]);

                Logger::log(Logger::INFO, Yii::t('app', 'Add Slide show success'), Yii::$app->user->identity->email);

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
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'SlideShow_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create SlideShow'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Add Slide show error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save slide show.'), Yii::$app->user->identity->email);

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
     * Updates an existing SlideShow model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $path = UploadedFile::getInstance($model, 'image');

        if($path) {
            $ext = $path->extension;
            // generate a unique file name
            $model->path = Yii::$app->security->generateRandomString().".{$ext}";
        }

        if ($model->load(Yii::$app->request->post())) {
            $oldModel = $model->oldAttributes;
            if ($model->save()) {
                if ($path) {
                    // directory to save image in local
                    $dir = Yii::getAlias('@frontend/web/uploads/slideshow/' . $model->id);
                    FileHelper::removeDirectory($dir);
                    FileHelper::createDirectory($dir);
                    $path->saveAs($dir . '/' . $model->path);
                }

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'SlideShow_Update_Success_Msg'),
                    'title' => Yii::t('app', 'Update SlideShow'),
                ]);

                Logger::log(Logger::INFO, Yii::t('app', 'Update Slide show success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);

                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'SlideShow_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update SlideShow'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Slide show error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Slide show has been edit error.'), Yii::$app->user->identity->email);

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
     * Deletes an existing SlideShow model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $slideshow = $this->findModel($id);
        $slideshow->active = SlideShow::STATUS_INACTIVE;
        if ($slideshow->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-trash-o',
                'message' => Yii::t('app', 'SlideShow_Delete_Success_Msg'),
                'title' => Yii::t('app', 'Delete SlideShow'),
            ]);
            Logger::log(Logger::INFO, Yii::t('app', 'Delete slide show success'), Yii::$app->user->identity->email);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-trash-o',
                'message' => current($slideshow->getFirstErrors()) ? current($slideshow->getFirstErrors()) : Yii::t('app', 'Could not delete this slide show. Please try again'),
                'title' => Yii::t('app', 'Delete SlideShow'),
            ]);
            Logger::log(Logger::ERROR, Yii::t('app', 'Delete slide show error: ') . current($slideshow->getFirstErrors()) ? current($slideshow->getFirstErrors()) : Yii::t('app', 'Slide show delete error.'), Yii::$app->user->identity->email);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the SlideShow model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SlideShow the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SlideShow::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
