<?php

namespace backend\controllers;

use common\models\Image;
use common\models\Offer;
use common\models\OrderDetails;
use common\models\ProductRating;
use common\models\ProductSeason;
use common\models\WishList;
use kartik\alert\Alert;
use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $product_id = Yii::$app->request->post('editableKey');
            $model = Product::findOne($product_id);

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
            $posted = current($_POST['Product']);
            $post['Product'] = $posted;

            $quantity = $model->quantity_in_stock;
            // load model like any single model validation
            if ($model->load($post)) {

                $output = '';
                $message = '';

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
                } else if (isset($posted['quantity_in_stock']) && $model->validate()) {
                    if ($quantity > $posted['quantity_in_stock']) {
                        $message = Yii::t('app', 'Quantity must be greater than ' . $quantity);
                    } else {
                        $model->save();
                    }
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
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $productImages = new Image();
        $product_seasons = new ProductSeason();

        $file = UploadedFile::getInstances($productImages, 'product_image');

        if ($model->load(Yii::$app->request->post()) && $product_seasons->load(Yii::$app->request->post())) {

            $model->create_date = strtotime('today');

            try {
                if ($model->save()) {
                    if ($file) {
                        $images = '';
                        foreach ($file as $image) {
                            // directory to save image in local
                            $dir = Yii::getAlias('@frontend/web/uploads/products/images/' . $model->id);
                            FileHelper::createDirectory($dir);
                            // path to save database
                            $path = 'uploads/products/images/' . $model->id . '/';

                            $productImages = new Image();
                            $productImages->product_id = $model->id;
                            $productImages->name = $image->name;
                            // generate random name for image save
                            $imageName = Yii::$app->getSecurity()->generateRandomString() . "." . $image->extension;
                            $productImages->path = $path . $imageName;
                            $images .= $imageName . $image->extension . '###';

                            $productImages->product_image = $images;
                            $productImages->save();

                            $image->saveAs($dir . '/' . $imageName);
                        }
                    }

                    foreach ($product_seasons->season_id as $product_season) {
                        $product_seasons->product_id = $model->id;
                        $product_seasons->season_id = $product_season;

                        $product_seasons->save();
                    }

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => Alert::TYPE_SUCCESS,
                        'duration' => 5000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'Product Record has been saved.'),
                        'title' => Yii::t('app', 'Add Product'),
                    ]);

                    switch (Yii::$app->request->post('action', 'save')) {
                        case 'next':
                            return $this->redirect(['create']);
                        default:
                            return $this->redirect(['index']);
                    }
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('danger', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Add Product'),
                ]);

                return $this->render('create', [
                    'model' => $model,
                    'productImages' => $productImages,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'productImages' => $productImages,
                'product_seasons' => $product_seasons
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $productImages = new Image();
        $product_seasons = ProductSeason::find()->select('season_id')->where(['product_id' => $id])->all();

        foreach($product_seasons as $product_season) {
            $model->product_seasons[] = $product_season->season_id;
        }

        $images = Image::find()->where(['product_id' => $id])->all();

        $file = UploadedFile::getInstances($productImages, 'product_image');

        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->save() && $file) {
                    foreach ($images as $image) {
                        $image->delete();
                    }

                    $dir = Yii::getAlias('@frontend/web/uploads/products/images/' . $id);
                    FileHelper::removeDirectory($dir);

                    $images = '';
                    foreach ($file as $image) {
                        FileHelper::createDirectory($dir);
                        // path to save database
                        $path = 'uploads/products/images/' . $model->id . '/';

                        $productImages = new Image();
                        $productImages->product_id = $model->id;
                        $productImages->name = $image->name;
                        // generate random name for image save
                        $imageName = Yii::$app->getSecurity()->generateRandomString() . "." . $image->extension;
                        $productImages->path = $path . $imageName;
                        $images .= $imageName . $image->extension . '###';

                        $productImages->product_image = $images;
                        $productImages->save();

                        $image->saveAs($dir . '/' . $imageName);
                    }
                }

                foreach ($model->product_seasons as $product_season) {
                    $productseason = ProductSeason::find()->where(['season_id' => $product_season])->andWhere(['product_id' => $model->id])->one();
                    $productseason->delete();
                }

                foreach ($product_seasons = Yii::$app->request->post('Product')['product_seasons'] as $season) {
                    $product_season = new ProductSeason();
                    $product_season->season_id = $season;
                    $product_season->product_id = $model->id;
                    $product_season->save();
                }

                Yii::$app->getSession()->setFlash('success', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 5000,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Product Record has been edited.'),
                    'title' => Yii::t('app', 'Edit Product'),
                ]);

                return $this->redirect(['index']);
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Edit Product'),
                ]);

                return $this->render('update', [
                    'model' => $model,
                    'productImages' => $productImages,
                    'images' => $images,
                    'product_seasons' => $product_seasons
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'productImages' => $productImages,
                'images' => $images,
                'product_seasons' => $product_seasons
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $product = $this->findModel($id);
        $product->active = Product::STATUS_INACTIVE;
        $product->save();

        Yii::$app->getSession()->setFlash('success', [
            'type' => Alert::TYPE_SUCCESS,
            'duration' => 3000,
            'icon' => 'fa fa-trash-o',
            'message' => 'Product Record has been deleted.',
            'title' => 'Delete Product'
        ]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
