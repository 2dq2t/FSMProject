<?php

namespace backend\controllers;

use backend\components\Logger;
use common\models\ProductSeason;
use Yii;
use common\models\Season;
use common\models\SeasonSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SeasonController implements the CRUD actions for Season model.
 */
class SeasonController extends Controller
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
     * Lists all Season models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SeasonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $season_id = Yii::$app->request->post('editableKey');
            $model = Season::findOne($season_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = Yii::t('app', 'The Season do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            // fetch the first entry in posted file (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted file for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['Season']);
            $post['Season'] = $posted;

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

                    Logger::log(Logger::INFO, Yii::t('app', 'Update Season'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Season error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
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
     * Displays a single Season model.
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
     * Creates a new Season model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Season();

        if ($model->from) {
            $model->from = date('d/m/Y', $model->from);
        }

        if ($model->to) {
            $model->to = date('d/m/Y', $model->to);
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->from = date_create_from_format('d/m/Y', $model->from) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $model->from)->format('m'), date_create_from_format('d/m/Y', $model->from)->format('d'), date_create_from_format('d/m/Y', $model->from)->format('y')) : time();
            $model->to = date_create_from_format('d/m/Y', $model->to) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $model->to)->format('m'), date_create_from_format('d/m/Y', $model->to)->format('d'), date_create_from_format('d/m/Y', $model->to)->format('y')) : time();

            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Season_Add_Success_Msg'),
                    'title' => Yii::t('app', 'Create Season')
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Add Season success'), Yii::$app->user->identity->email);
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->redirect(['create']);
                    default:
                        return $this->redirect(['index']);
                }
            } else {
                if ($model->from) {
                    $model->from = date('d/m/Y', $model->from);
                }

                if ($model->to) {
                    $model->to = date('d/m/Y', $model->to);
                }

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app','Season_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create Season'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Add Season error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save season.'), Yii::$app->user->identity->email);
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
     * Updates an existing Season model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->from) {
            $model->from = date('d/m/Y', $model->from);
        }

        if ($model->to) {
            $model->to = date('d/m/Y', $model->to);
        }

        if ($model->load(Yii::$app->request->post())) {

            $oldModel = $model->oldAttributes;
            $model->from = date_create_from_format('d/m/Y', $model->from) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $model->from)->format('m'), date_create_from_format('d/m/Y', $model->from)->format('d'), date_create_from_format('d/m/Y', $model->from)->format('y')) : time();
            $model->to = date_create_from_format('d/m/Y', $model->to) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $model->to)->format('m'), date_create_from_format('d/m/Y', $model->to)->format('d'), date_create_from_format('d/m/Y', $model->to)->format('y')) : time();

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Season_Update_Success_Msg'),
                    'title' => Yii::t('app', 'Update Season')
                ]);

                Logger::log(Logger::INFO, Yii::t('app', 'Update Season success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);

                return $this->redirect(['index']);
            } else {
                if ($model->from) {
                    $model->from = date('d/m/Y', $model->from);
                }

                if ($model->to) {
                    $model->to = date('d/m/Y', $model->to);
                }

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app','Season_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update Season'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update Season error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Season has been edit error.'), Yii::$app->user->identity->email);

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
     * Deletes an existing Season model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ProductSeason::deleteAll(['season_id'=>$id]);

        $season = $this->findModel($id);
        $season->active = Season::STATUS_INACTIVE;
        if ($season->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-trash-o',
                'message' => Yii::t('app', 'Season_Delete_Success_Msg'),
                'title' => Yii::t('app', 'Delete Season')
            ]);
            Logger::log(Logger::INFO, Yii::t('app', 'Delete season success'), Yii::$app->user->identity->email);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-trash-o',
                'message' => current($season->getFirstErrors()) ? current($season->getFirstErrors()) : Yii::t('app', 'Could not delete this category. Please try again.'),
                'title' => Yii::t('app', 'Delete Season')
            ]);
            Logger::log(Logger::ERROR, Yii::t('app', 'Delete season error: ') . current($season->getFirstErrors()) ? current($season->getFirstErrors()) : Yii::t('app', 'Season delete error.'), Yii::$app->user->identity->email);
        }

        return $this->redirect(['index']);
    }

    public function actionDetails($id) {
        $model = $this->findModel($id);

        $model->setProductsList(ArrayHelper::map(ProductSeason::find()->where(['season_id' => $id])->all(), 'product_id', 'product_id'));
        $products_list = $model->getProductsList();
        $model->setProductsList(Json::encode($model->getProductsList()));

        if (Yii::$app->request->isPost) {

            if (!$model->load(Yii::$app->request->post())) {
                $model->setProductsList([]);
            }

            $transaction = Yii::$app->db->beginTransaction();

            $product_lists = $model->getProductsList();

            try {
                // check if user remove product then remove this product from product season
                foreach ($products_list as $product_list) {
                    $key = array_search($product_list, $product_lists);
                    if ($key === false) {
                        ProductSeason::find()->where(['product_id' => $product_list])->andWhere(['season_id' => $model->id])->one()->delete();
                    } else {
                        unset($product_lists[$key]);
                    }
                }

                // insert new product_id to product_season
                foreach ($product_lists as $product_list) {
                    $product_season = new ProductSeason();
                    $product_season->product_id = $product_list;
                    $product_season->season_id = $model->id;
                    $product_season->save();
                }

                $transaction->commit();

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Product season has been added.'),
                    'title' => Yii::t('app', 'Add Product season')
                ]);

            } catch (Exception $e) {
                $transaction->rollBack();
                $model->setProductsList(Json::encode($model->getProductsList()));

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Could not be add product. Try again later.'),
                    'title' => Yii::t('app', 'Add Product season')
                ]);
            }

            $model->setProductsList(Json::encode($products_list));

            return $this->redirect(['season/details', 'id' => $model->id]);
        }

        return $this->render('details', [
            'model' => $model
        ]);
    }

    /**
     * Finds the Season model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Season the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Season::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
