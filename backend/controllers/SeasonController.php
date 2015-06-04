<?php

namespace backend\controllers;

use common\models\Product;
use common\models\ProductSeason;
use kartik\alert\Alert;
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
                $message = 'The Season do not exist.';
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
                    $message = $model->errors;
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
            $model->from = date('m/d/Y', $model->from);
        }

        if ($model->to) {
            $model->to = date('m/d/Y', $model->to);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->from = strtotime($model->from);
            $model->to = strtotime($model->to);

            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => 'Season Record has been saved.',
                    'title' => 'Add Season'
                ]);
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'next':
                        return $this->redirect(['create']);
                    default:
                        return $this->redirect(['index']);
                }
            } else {
                if ($model->from) {
                    $model->from = date('m/d/Y', $model->from);
                }

                if ($model->to) {
                    $model->to = date('m/d/Y', $model->to);
                }

                Yii::$app->getSession()->setFlash('danger', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : 'Could not be save the season',
                    'title' => Yii::t('app', 'Add Season'),
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
     * Updates an existing Season model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->from) {
            $model->from = date('m/d/Y', $model->from);
        }

        if ($model->to) {
            $model->to = date('m/d/Y', $model->to);
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->from = strtotime($model->from);
            $model->to = strtotime($model->to);

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 5000,
                    'icon' => 'fa fa-pencil',
                    'message' => 'Season Record has been updated.',
                    'title' => 'Update Season'
                ]);
                return $this->redirect(['index']);
            } else {
                if ($model->from) {
                    $model->from = date('m/d/Y', $model->from);
                }

                if ($model->to) {
                    $model->to = date('m/d/Y', $model->to);
                }

                Yii::$app->getSession()->setFlash('danger', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 3000,
                    'icon' => 'fa fa-pencil',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : 'Could not be save the season',
                    'title' => Yii::t('app', 'Edit Season'),
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
     * Deletes an existing Season model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ProductSeason::updateAll(['active' => ProductSeason::STATUS_INACTIVE], ['season_id' => $id]);

        $season = $this->findModel($id);
        $season->active = Season::STATUS_INACTIVE;
        $season->save();

        Yii::$app->getSession()->setFlash('success', [
            'type' => Alert::TYPE_SUCCESS,
            'duration' => 5000,
            'icon' => 'fa fa-trash-o',
            'message' => 'Season Record has been deleted.',
            'title' => 'Delete Season'
        ]);

        return $this->redirect(['index']);
    }

    public function actionDetails($id) {
        $model = $this->findModel($id);

        $model->products_list = ArrayHelper::map(ProductSeason::find()->where(['season_id' => $id])->all(), 'product_id', 'product_id');
        $products_list = $model->products_list;
        $model->products_list = Json::encode($model->products_list);

        if (Yii::$app->request->isPost) {

            if (!$model->load(Yii::$app->request->post())) {
                $model->products_list = [];
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                // check if user remove product then remove this product from product season
                foreach ($products_list as $product_list) {
                    $key = array_search($product_list, $model->products_list);
                    if ($key === false) {
                        ProductSeason::find()->where(['product_id' => $product_list])->andWhere(['season_id' => $model->id])->one()->delete();
                    } else {
                        unset($model->products_list[$key]);
                    }
                }

                // insert new product_id to product_season
                foreach ($model->products_list as $product_list) {
                    $product_season = new ProductSeason();
                    $product_season->product_id = $product_list;
                    $product_season->season_id = $model->id;
                    $product_season->save();
                }

                $transaction->commit();

                Yii::$app->getSession()->setFlash('success', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 5000,
                    'icon' => 'fa fa-plus',
                    'message' => 'Product season has been added.',
                    'title' => 'Add Product season'
                ]);

            } catch (Exception $e) {
                $transaction->rollBack();
                $model->products_list = Json::encode($model->products_list);

                Yii::$app->getSession()->setFlash('error', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 5000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Product season add failure. Try again later.'),
                    'title' => 'Add Product season'
                ]);
            }

            $model->products_list = Json::encode($products_list);

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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
