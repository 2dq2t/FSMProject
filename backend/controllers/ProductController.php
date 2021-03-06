<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\components\ParserDateTime;
use backend\models\TmpProduct;
use common\models\Image;
use common\models\ProductSeason;
use common\models\ProductTag;
use common\models\Tag;
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
            'access' => [
                'class' => YII_DEBUG ?  \yii\base\ActionFilter::className() : \backend\components\AccessControl::className()
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
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Product'), Yii::$app->user->identity->email, $oldModel, $model->attributes);

                } else if (isset($posted['quantity_in_stock']) && $model->validate()) {
                    if ($quantity > $posted['quantity_in_stock']) {
                        $message = Yii::t('app', 'Quantity must be greater than ' . $quantity);
                    } else {
                        $model->save();
                    }
                    Logger::log(Logger::INFO, Yii::t('app', 'Update Product'), Yii::$app->user->identity->email, $oldModel, $model->attributes);
                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Product error') . ' ' . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : '', Yii::$app->user->identity->email);
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
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        if (!Yii::$app->request->post()) {
            // get barcode
            // delete all if last use greater than 1 day
            /** @var  $tmp TmpProduct */
            foreach (TmpProduct::find()
                         ->where(['status' => TmpProduct::STATUS_ACTIVE])
                         ->andWhere(['IS NOT', 'last_used', null])->all() as $tmp) {
                // if last used greater than 1 day then delete
                if (ParserDateTime::getTimeStamp() - $tmp->last_used > 1800) {
                    $tmp->status = TmpProduct::STATUS_INACTIVE;
                    $tmp->last_used = null;
                    $tmp->save();
                }
            }

            /** @var  $tmpId TmpProduct */
            $tmpId = TmpProduct::find()
                ->where('status = ' . TmpProduct::STATUS_INACTIVE)
                ->andWhere(['IS', 'last_used', null])
                ->orderBy(['id' => SORT_ASC])->one();
            $tmpId->status = TmpProduct::STATUS_ACTIVE;
            $tmpId->last_used = ParserDateTime::getTimeStamp();
            $tmpId->update();

            $barcode = Yii::$app->params['barcodeCountryCode'] . Yii::$app->params['barcodeBusinessCode'] . $tmpId->id;
            $sum = 0;
            for ($i = strlen($barcode) - 1; $i >= 0; $i--) {
                if ($i % 2 != 0) {
                    // 1. sum each of the even numbered digits
                    // 2. multiply result by three
                    $sum += $barcode[$i] * 3;
                } else {
                    // 3. sum of each of the odd numbered digits
                    $sum += $barcode[$i];
                }
            }

            // 4. subtract the result from the next highest power of 10
            $checkSum = $sum % 10 != 0 ? 10 - $sum % 10 : 0;

            $model->barcode = $tmpId->id . $checkSum;
        }

        if ($model->load(Yii::$app->request->post())) {
//            var_dump($model->barcode);return;
            // set create date as timestamp
            $model->create_date = ParserDateTime::getTimeStamp();
//            $model->barcode = $tmpId->id . $checkSum;

            $product_images = UploadedFile::getInstances($model, 'productImage');

            // remove space in product price
            $model->price = preg_replace('/\s/', '', $model->price);

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->validate() && $model->save()) {
                    $errors = [];
                    // directory to save image in local
                    $dir = Yii::getAlias('@frontend/web/uploads/products/images/' . $model->id);
                    $dir_resize = Yii::getAlias('@frontend/web/uploads/products/resizeimages/' . $model->id);
                    FileHelper::createDirectory($dir);
                    FileHelper::createDirectory($dir_resize);
                    foreach ($product_images as $image) {
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

                        if($productImages->save())
                        {
                            $image->saveAs($dir . '/' . $imageName);
                            $this->resizeImage($dir . '/' . $imageName, $dir_resize . '/' . $imageName, 100, 100, $image->type);
                            Logger::log(Logger::INFO, Yii::t('app', 'Add product image success'), Yii::$app->user->identity->email);
                        } else {
                            $errors[] = current($productImages->getFirstErrors()) ? current($productImages->getFirstErrors()) : Yii::t('app', 'Could not save image');
                            Logger::log(Logger::ERROR, Yii::t('app', 'Add product image error:'). current($productImages->getFirstErrors()) ? current($productImages->getFirstErrors()) : Yii::t('app', 'Could not save image'), Yii::$app->user->identity->email);
                        }
                    }

                    if (empty($model->getProductSeasons())) {
                        $model->setProductSeasons([]);
                    }

                    foreach ($model->getProductSeasons() as $product_season) {
                        $product_seasons = new ProductSeason();
                        $product_seasons->product_id = $model->id;
                        $product_seasons->season_id = $product_season;

                        if(!$product_seasons->save()) {
                            $errors[] = current($product_seasons->getFirstErrors()) ? current($product_seasons->getFirstErrors()) : Yii::t('app', 'Could not save product season.');
                            Logger::log(Logger::ERROR, Yii::t('app', 'Add Product season error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product season.'), Yii::$app->user->identity->email);
                        }
                        Logger::log(Logger::INFO, Yii::t('app', 'Add Product season success'), Yii::$app->user->identity->email);
                    }

                    if (empty($model->getProductSeasons())) {
                        $model->setProductTags([]);
                    }

                    foreach ($model->getProductTags() as $product_tag) {
                        // check tag exists
                        if (Tag::find()->where(['id' => $product_tag])->exists()) {
                            $product_tags = new ProductTag();
                            $product_tags->tag_id = $product_tag;
                            $product_tags->product_id = $model->id;
                            if (!$product_tags->save())
                            {
                                $errors[] = current($product_tags->getFirstErrors()) ? current($product_tags->getFirstErrors()) : Yii::t('app', 'Could not save product tag.');
                                Logger::log(Logger::ERROR, Yii::t('app', 'Add Product Tag error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product tags.'), Yii::$app->user->identity->email);
                            }
                            Logger::log(Logger::INFO, Yii::t('app', 'Add Product Tag success'), Yii::$app->user->identity->email);

                        } else {
                            // save if tag do not exists
                            $tag = new Tag();
                            $tag->name = $product_tag;
                            if ($tag->save()) {
                                $product_tags = new ProductTag();
                                $product_tags->tag_id = $tag->id;
                                $product_tags->product_id = $model->id;
                                if ($product_tags->save())
                                {
                                    Logger::log(Logger::INFO, Yii::t('app', 'Add Tag success'), Yii::$app->user->identity->email);
                                    Logger::log(Logger::INFO, Yii::t('app', 'Add Product Tag success'), Yii::$app->user->identity->email);
                                } else {
                                    $errors[] = current($product_tags->getFirstErrors()) ? current($product_tags->getFirstErrors()) : Yii::t('app', 'Could not save product tag,');
                                    Logger::log(Logger::ERROR, Yii::t('app', 'Add Product Tag error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product tags.'), Yii::$app->user->identity->email);
                                }
                            } else {
                                $errors[] = current($tag->getFirstErrors()) ? current($tag->getFirstErrors()) : Yii::t('app', 'Could not add tag');
                                Logger::log(Logger::ERROR, Yii::t('app', 'Add Tag error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save tags.'), Yii::$app->user->identity->email);
                            }
                        }
                    }

                    $tmp = TmpProduct::find()
                        ->where(['status' => TmpProduct::STATUS_ACTIVE])
                        ->andWhere(['IS NOT', 'last_used', null])
                        ->andWhere(['id' => substr($model->barcode, 0, 4)])->one();
                    $tmp->last_used = null;

                    if (!$tmp->update()) $errors[] = current($tmp->getFirstErrors()) ? current($tmp->getFirstErrors()) : Yii::t('app', 'Could not update temp barcode.');

                    if (!empty($errors)) {
                        if ($transaction->getIsActive()) {
                            $transaction->rollBack();
                        }

                        FileHelper::removeDirectory($dir);
                        FileHelper::removeDirectory($dir_resize);

                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => $errors[0],
                            'title' => Yii::t('app', 'Create Product'),
                        ]);
                        Logger::log(Logger::ERROR, Yii::t('app', 'Add Product error: ') . $errors[0], Yii::$app->user->identity->email);

                        return $this->render('create', [
                            'model' => $model
                        ]);
                    }

                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'Product_Add_Success_Msg'),
                        'title' => Yii::t('app', 'Create Product'),
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
                        'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Product_Add_Error_Msg'),
                        'title' => Yii::t('app', 'Create Product'),
                    ]);
                    $model->price = number_format($model->price, 0, '', ' ');
                    Logger::log(Logger::ERROR, Yii::t('app', 'Add Product error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product.'), Yii::$app->user->identity->email);
                    return $this->render('create', [
                        'model' => $model
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
                    'message' => $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Product_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create Product'),
                ]);
                $model->price = number_format($model->price, 0, '', ' ');
                Logger::log(Logger::ERROR, Yii::t('app', 'Add Product error: ') . $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Product_Add_Error_Msg'), Yii::$app->user->identity->email);

                return $this->render('create', [
                    'model' => $model
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model
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
        $images = Image::find()->where(['product_id' => $id])->all();
        $product_seasons = ProductSeason::find()->select('season_id')->where(['product_id' => $id])->all();
        $product_tags = ProductTag::find()->select('tag_id')->where(['product_id' => $id])->all();

        $model->setProductSeasons(ArrayHelper::map($product_seasons, 'season_id', 'season_id'));
        $model->setProductTags(ArrayHelper::map($product_tags, 'tag_id', 'tag_id'));

        $model->price = number_format($model->price, 0, '', ' ');

        if ($model->load(Yii::$app->request->post())) {
            $oldModel = $model->oldAttributes;
            $model->price = preg_replace('/\s/', '', $model->price);
            $file = UploadedFile::getInstances($model, 'productImage');

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $errors = [];
                    if ($file) {
                        foreach ($images as $image) {
                            $image->delete();
                        }

                        $dir = Yii::getAlias('@frontend/web/uploads/products/images/' . $id);
                        $dir_resize = Yii::getAlias('@frontend/web/uploads/products/resizeimages/' . $id);
                        FileHelper::removeDirectory($dir);
                        FileHelper::removeDirectory($dir_resize);

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
                            if($productImages->save())
                            {
                                $image->saveAs($dir . '/' . $imageName);
                                $this->resizeImage($dir . '/' . $imageName, $dir_resize . '/' . $imageName, 100, 100, $image->type);
                                Logger::log(Logger::INFO, Yii::t('app', 'Add product image success'), Yii::$app->user->identity->email);
                            } else {
                                $errors[] = current($productImages->getFirstErrors()) ? current($productImages->getFirstErrors()) : Yii::t('app', 'Could not save image');
                                Logger::log(Logger::ERROR, Yii::t('app', 'Add product image error:'). current($productImages->getFirstErrors()) ? current($productImages->getFirstErrors()) : Yii::t('app', 'Could not save image'), Yii::$app->user->identity->email);
                            }
                        }
                    }

                    if (empty($model->getProductSeasons())) {
                        $model->setProductSeasons([]);
                    }

                    $productSeasons = $model->getProductSeasons();

                    foreach ($product_seasons as $product_season) {
                        $key = array_search($product_season->season_id, $productSeasons);
                        if ($key === false) {
                            ProductSeason::find()->where(['season_id' => $product_season->season_id])->andWhere(['product_id' => $id])->one()->delete();
                        } else {
                            unset($productSeasons[$key]);
                        }
                    }

                    foreach ($productSeasons as $season) {
                        $product_season = new ProductSeason();
                        $product_season->season_id = $season;
                        $product_season->product_id = $model->id;
                        if(!$product_season->save()) {
                            $errors[] = current($product_season->getFirstErrors()) ? current($product_season->getFirstErrors()) : Yii::t('app', 'Could not save product season.');
                            Logger::log(Logger::ERROR, Yii::t('app', 'Add Product season error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product season.'), Yii::$app->user->identity->email);
                        }
                        Logger::log(Logger::INFO, Yii::t('app', 'Add Product season success'), Yii::$app->user->identity->email);
                    }

                    if (empty($model->getProductTags())) {
                        $model->setProductTags([]);
                    }

                    $productTags = $model->getProductTags();

                    foreach ($product_tags as $tag) {
                        $key = array_search($tag->tag_id, $productTags);

                        if ($key === false) {
                            ProductTag::find()->where(['tag_id' => $tag->tag_id])->andWhere(['product_id' => $id])->one()->delete();
                        } else {
                            unset($productTags[$key]);
                        }
                    }

                    foreach ($productTags as $tag) {
                        // check tag exists
                        if (Tag::find()->where(['id' => $tag])->exists()) {
                            $product_tag = new ProductTag();
                            $product_tag->tag_id = $tag;
                            $product_tag->product_id = $model->id;
                            if (!$product_tag->save())
                            {
                                $errors[] = current($product_tag->getFirstErrors()) ? current($product_tag->getFirstErrors()) : Yii::t('app', 'Could not save product tag');
                                Logger::log(Logger::ERROR, Yii::t('app', 'Add Product Tag error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product tags.'), Yii::$app->user->identity->email);
                            }
                            Logger::log(Logger::INFO, Yii::t('app', 'Add Product Tag success'), Yii::$app->user->identity->email);

                        } else {
                            // save if tag do not exists
                            $tags = new Tag();
                            $tags->name = $tag;
                            if ($tags->save()) {
                                $product_tag = new ProductTag();
                                $product_tag->tag_id = $tags->id;
                                $product_tag->product_id = $model->id;
                                if (!$product_tag->save())
                                {
                                    $errors[] = current($product_tag->getFirstErrors()) ? current($product_tag->getFirstErrors()) : Yii::t('app', 'Could not save product tag.');
                                    Logger::log(Logger::ERROR, Yii::t('app', 'Add Product Tag error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save product tags.'), Yii::$app->user->identity->email);
                                }
                                Logger::log(Logger::INFO, Yii::t('app', 'Add Tag success'), Yii::$app->user->identity->email);
                                Logger::log(Logger::INFO, Yii::t('app', 'Add Product Tag success'), Yii::$app->user->identity->email);
                            } else {
                                $errors[] = current($tags->getFirstErrors()) ? current($tags->getFirstErrors()) : Yii::t('app', 'Could not add tag');
                                Logger::log(Logger::ERROR, Yii::t('app', 'Add Tag error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save tags.'), Yii::$app->user->identity->email);
                            }
                        }
                    }

                    if (!empty($errors)) {
                        if ($transaction->getIsActive()) {
                            $transaction->rollBack();
                        }

                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => $errors[0],
                            'title' => Yii::t('app', 'Update Product'),
                        ]);

                        return $this->render('create', [
                            'model' => $model,
                            'images' => $images
                        ]);
                    }

                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 0,
                        'icon' => 'fa fa-pencil',
                        'message' => Yii::t('app', 'Product_Update_Success_Msg'),
                        'title' => Yii::t('app', 'Update Product'),
                    ]);

                    Logger::log(Logger::INFO, Yii::t('app', 'Product_Update_Success_Msg'), Yii::$app->user->identity->email, $oldModel, $model->attributes);

                    return $this->redirect(['index']);
                } else {

                    if ($transaction->getIsActive()) {
                        $transaction->rollBack();
                    }

                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Product_Update_Error_Msg'),
                        'title' => Yii::t('app', 'Update Product'),
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Product error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Product_Update_Error_Msg'), Yii::$app->user->identity->email);

                    return $this->render('create', [
                        'model' => $model,
                        'images' => $images
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
                    'message' => $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Product_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update Product'),
                ]);
                Logger::log(Logger::ERROR, Yii::t('app', 'Update Product error: ') . $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Product_Update_Error_Msg'), Yii::$app->user->identity->email);

                return $this->render('update', [
                    'model' => $model,
                    'images' => $images,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
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
//    public function actionDelete($id)
//    {
//        $product = $this->findModel($id);
//        $product->active = Product::STATUS_INACTIVE;
//        if ($product->save()) {
//            Logger::log(Logger::INFO, Yii::t('app', 'Delete product success'), Yii::$app->user->identity->email);
//            Yii::$app->getSession()->setFlash('success', [
//                'type' => 'success',
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => Yii::t('app', 'Product_Delete_Success_Msg'),
//                'title' => Yii::t('app', 'Delete Product')
//            ]);
//        } else {
//            Logger::log(Logger::ERROR, Yii::t('app', 'Delete product error: ') . current($product->getFirstErrors()) ? current($product->getFirstErrors()) : Yii::t('app', 'Product delete error.'), Yii::$app->user->identity->email);
//            Yii::$app->getSession()->setFlash('error', [
//                'type' => 'error',
//                'duration' => 0,
//                'icon' => 'fa fa-trash-o',
//                'message' => current($product->getFirstErrors()) ? current($product->getFirstErrors()) : Yii::t('app', 'Could not delete this product.'),
//                'title' => Yii::t('app', 'Delete Product')
//            ]);
//        }
//
//        return $this->redirect(['index']);
//    }

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
