<?php

namespace backend\controllers;

use common\models\Image;
use common\models\ProductSeason;
use common\models\ProductTag;
use common\models\Tag;
use Faker\Provider\File;
use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $productImages = new Image();
        $product_seasons = new ProductSeason();
        $product_tags = new ProductTag();

        $model->price = number_format($model->price, 0, '', ' ');

        if ($model->load(Yii::$app->request->post())) {

            // set create date as timestamp
            $model->create_date = strtotime('today');

            $file = UploadedFile::getInstances($productImages, 'product_image');

            // remove space in product price
            $model->price = preg_replace('/\s/', '', $model->price);

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->save()) {
                    if ($file) {
                        $images = '';
                        foreach ($file as $image) {
                            // directory to save image in local
                            $dir = Yii::getAlias('@frontend/web/uploads/products/images/' . $model->id);
                            $dir_resize = Yii::getAlias('@frontend/web/uploads/products/resizeimages/' . $model->id);
                            FileHelper::createDirectory($dir);
                            FileHelper::createDirectory($dir_resize);
                            // path to save database
                            $path = 'uploads/products/images/' . $model->id . '/';
                            $path_resize = 'uploads/products/resizeimages/' . $model->id . '/';

                            $productImages = new Image();
                            $productImages->product_id = $model->id;
                            $productImages->name = $image->name;
                            // generate random name for image save
                            $imageName = Yii::$app->getSecurity()->generateRandomString() . "." . $image->extension;
                            $productImages->path = $path . $imageName;
                            $productImages->resize_path = $path_resize . $imageName;
                            $images .= $imageName . $image->extension . '###';

                            $productImages->product_image = $images;
                            $productImages->save();

                            $image->saveAs($dir . '/' . $imageName);
                            $this->resizeImage($dir . '/' . $imageName, $dir_resize . '/' . $imageName, 100, 100, $image->type);
                        }
                    }

                    if (empty($model->product_seasons)) {
                        $model->product_seasons = [];
                    }

                    foreach ($model->product_seasons as $product_season) {
                        $product_seasons->product_id = $model->id;
                        $product_seasons->season_id = $product_season;

                        $product_seasons->save();
                    }

                    if (empty($model->product_tags)) {
                        $model->product_tags = [];
                    }

                    foreach ($model->product_tags as $product_tag) {
                        // check tag exists
                        if (Tag::find()->where(['id' => $product_tag])->exists()) {
                            $product_tags->tag_id = $product_tag;
                            $product_tags->product_id = $model->id;
                            $product_tags->save();
                        } else {
                            // save if tag do not exists
                            $tag = new Tag();
                            $tag->name = $product_tag;
                            $tag->save();

                            $product_tags->tag_id = $tag->id;
                            $product_tags->product_id = $model->id;
                            $product_tags->save();
                        }
                    }

                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
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
                } else {
                    if ($transaction->getIsActive()) {
                        $transaction->rollBack();
                    }
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product. Please try again'),
                        'title' => Yii::t('app', 'Add Product'),
                    ]);

                    return $this->render('create', [
                        'model' => $model,
                        'productImages' => $productImages
                    ]);
                }
            } catch (Exception $e) {
                if ($transaction->getIsActive()) {
                    $transaction->rollBack();
                }
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Could not save product. Please try again'),
                    'title' => Yii::t('app', 'Add Product'),
                ]);

                return $this->render('create', [
                    'model' => $model,
                    'productImages' => $productImages
                ]);
            }

        } else {

            return $this->render('create', [
                'model' => $model,
                'productImages' => $productImages
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $productImages = new Image();
        $product_seasons = ProductSeason::find()->select('season_id')->where(['product_id' => $id])->all();
        $product_tags = ProductTag::find()->select('tag_id')->where(['product_id' => $id])->all();

        $model->product_seasons = ArrayHelper::map($product_seasons, 'season_id', 'season_id');
        $model->product_tags = ArrayHelper::map($product_tags, 'tag_id', 'tag_id');
        $images = Image::find()->where(['product_id' => $id])->all();

        $model->price = number_format($model->price, 0, '', ' ');

        if ($model->load(Yii::$app->request->post())) {
            $model->price = preg_replace('/\s/', '', $model->price);
            $file = UploadedFile::getInstances($productImages, 'product_image');

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {

                    if ($file) {
                        foreach ($images as $image) {
                            $image->delete();
                        }

                        $dir = Yii::getAlias('@frontend/web/uploads/products/images/' . $id);
                        $dir_resize = Yii::getAlias('@frontend/web/uploads/products/resizeimages/' . $id);
                        FileHelper::removeDirectory($dir);
                        FileHelper::removeDirectory($dir_resize);

                        $images = '';
                        foreach ($file as $image) {
                            FileHelper::createDirectory($dir);
                            FileHelper::createDirectory($dir_resize);
                            // path to save database
                            $path = 'uploads/products/images/' . $model->id . '/';
                            $path_resize = 'uploads/products/resizeimages/' . $model->id . '/';

                            $productImages = new Image();
                            $productImages->product_id = $model->id;
                            $productImages->name = $image->name;
                            // generate random name for image save
                            $imageName = Yii::$app->getSecurity()->generateRandomString() . "." . $image->extension;
                            $productImages->path = $path . $imageName;
                            $productImages->resize_path = $path_resize . $imageName;
                            $images .= $imageName . $image->extension . '###';

                            $productImages->product_image = $images;
                            $productImages->save();

                            $image->saveAs($dir . '/' . $imageName);
                            $this->resizeImage($dir . '/' . $imageName, $dir_resize . '/' . $imageName, 100, 100, $image->type);
                        }
                    }

                    if (empty($model->product_seasons)) {
                        $model->product_seasons = [];
                    }

                    foreach ($product_seasons as $product_season) {
                        $key = array_search($product_season->season_id, $model->product_seasons);
                        if ($key === false) {
                            ProductSeason::find()->where(['season_id' => $product_season->season_id])->andWhere(['product_id' => $id])->one()->delete();
                        } else {
                            unset($model->product_seasons[$key]);
                        }
                    }

                    foreach ($model->product_seasons as $season) {
                        $product_season = new ProductSeason();
                        $product_season->season_id = $season;
                        $product_season->product_id = $model->id;
                        $product_season->save();
                    }

                    if (empty($model->product_tags)) {
                        $model->product_tags = [];
                    }

                    foreach ($product_tags as $tag) {
                        $key = array_search($tag->tag_id, $model->product_tags);

                        if ($key === false) {
                            ProductTag::find()->where(['tag_id' => $tag->tag_id])->andWhere(['product_id' => $id])->one()->delete();
                        } else {
                            unset($model->product_tags[$key]);
                        }
                    }

                    foreach ($model->product_tags as $tag) {
                        // check tag exists
                        if (Tag::find()->where(['id' => $tag])->exists()) {
                            $product_tag = new ProductTag();
                            $product_tag->tag_id = $tag;
                            $product_tag->product_id = $model->id;
                            $product_tag->save();
                        } else {
                            // save if tag do not exists
                            $tags = new Tag();
                            $tags->name = $tag;
                            $tags->save();

                            $product_tag = new ProductTag();
                            $product_tag->tag_id = $tags->id;
                            $product_tag->product_id = $model->id;
                            $product_tag->save();
                        }
                    }

                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 0,
                        'icon' => 'fa fa-pencil',
                        'message' => Yii::t('app', 'Product Record has been edited.'),
                        'title' => Yii::t('app', 'Edit Product'),
                    ]);

                    return $this->redirect(['index']);
                } else {

                    if ($transaction->getIsActive()) {
                        $transaction->rollBack();
                    }

                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product. Please try again'),
                        'title' => Yii::t('app', 'Edit Product'),
                    ]);

                    return $this->render('create', [
                        'model' => $model,
                        'productImages' => $productImages,
                        'images' => $images,
                        'product_seasons' => $product_seasons
                    ]);
                }
            } catch (Exception $e) {

                if ($transaction->getIsActive()) {
                    $transaction->rollBack();
                }
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Could not save product. Please try again'),
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
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $product = $this->findModel($id);
        $product->active = Product::STATUS_INACTIVE;
        $product->save();

        Yii::$app->getSession()->setFlash('success', [
            'type' => 'success',
            'duration' => 3000,
            'icon' => 'fa fa-trash-o',
            'message' => Yii::t('app', 'Product Record has been deleted.'),
            'title' => Yii::t('app', 'Delete Product')
        ]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Image resize
     * @param string $original_image
     * @param string $resize_path
     * @param int $width
     * @param int $height
     * @param string $type
     */
    private function resizeImage($original_image, $resize_path, $width, $height, $type)
    {
        /* Get original image*/
        list($w_original, $h_original) = getimagesize($original_image);
        /* calculate new image size with ratio */
        $ratio = max($width / $w_original, $height / $h_original);
        $w_original = ceil($width / $ratio);
        $h_original = ceil($height / $ratio);
        /* read binary data from image file */
        $imgString = file_get_contents($original_image);
        /* create image from string */
        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $image,
            0, 0,
            0, 0,
            $width, $height,
            $w_original, $h_original);
        /* Save image */
        switch ($type) {
            case 'image/jpeg':
                imagejpeg($tmp, $resize_path, 100);
                break;
            case 'image/png':
                imagepng($tmp, $resize_path, 0);
                break;
            case 'image/gif':
                imagegif($tmp, $resize_path);
                break;
            default:
                exit;
                break;
        }
        /* cleanup memory */
        imagedestroy($image);
        imagedestroy($tmp);
    }
}
