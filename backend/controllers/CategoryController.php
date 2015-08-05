<?php

namespace backend\controllers;

use backend\components\Logger;
use Yii;
use common\models\Category;
use common\models\CategorySearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @var $model Category
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $category_id = Yii::$app->request->post('editableKey');
            $model = Category::findOne($category_id);
            // $model = Category::findModel($categoryId);

            // store a default json response as desired by editable
            $out = Json::encode(['output'=>'', 'message'=>'']);

            // fetch the first entry in posted file (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted file for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['Category']);
            $post['Category'] = $posted;

            // load model like any single model validation
            if ($model->load($post)) {
                $output = '';
                $message = '';
                $oldModel = $model->oldAttributes;

                if(isset($posted['active']) && $model->validate() && $model->save()) {
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
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Category success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Category error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
                }

                $out = Json::encode(['output'=>$output, 'message'=>$message]);
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }

        $dataProvider->setPagination(['pageSize' => 7]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) ) {

            if ($model->save()) {

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Category_Add_Success_Msg.'),
                    'title' => Yii::t('app', 'Create Category')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Add Category success'), Yii::$app->user->identity->email);
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->redirect(['create']);
                    default:
                        return $this->redirect(['index']);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save category.'),
                    'title' => Yii::t('app', 'Create Category')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Add Category error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save category.'), Yii::$app->user->identity->email);
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
     * Updates an existing Category model.
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
                    'message' => Yii::t('app', 'Category_Update_Success_Msg.'),
                    'title' => Yii::t('app', 'Update Category')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Update Category success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Category_Update_Error_Msg.'),
                    'title' => Yii::t('app', 'Update Category')
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Category error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Category has been edit error.'), Yii::$app->user->identity->email);

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
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $category = $this->findModel($id);
        $category->active = Category::STATUS_INACTIVE;
        if($category->save()){
            Logger::log(Logger::INFO, Yii::t('app', 'Delete category success'), Yii::$app->user->identity->email);
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-trash-o',
                'message' => Yii::t('app', 'Category_Delete_Success_Msg.'),
                'title' => Yii::t('app', 'Delete Category')
            ]);
        } else {
            Logger::log(Logger::ERROR, Yii::t('app', 'Delete category error: ') . current($category->getFirstErrors()) ? current($category->getFirstErrors()) : Yii::t('app', 'Category delete error.'), Yii::$app->user->identity->email);
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-trash-o',
                'message' => current($category->getFirstErrors()) ? current($category->getFirstErrors()) : Yii::t('app', 'Category delete error.'),
                'title' => Yii::t('app', 'Delete Category')
            ]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
