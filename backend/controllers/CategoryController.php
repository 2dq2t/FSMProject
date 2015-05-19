<?php

namespace backend\controllers;

use common\models\Image;
use common\models\Offer;
use common\models\OrderDetails;
use common\models\Product;
use common\models\ProductSeason;
use common\models\WishList;
use kartik\alert\Alert;
use Yii;
use common\models\Category;
use common\models\CategorySearch;
use yii\base\Exception;
use yii\helpers\FileHelper;
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
                $message = '';
                if($model->validate()) {
                    // can save model or do something before saving model
                    $model->save();
                    // custom output to return to be displayed as the editable grid cell
                    // file. Normally this is empty - whereby whatever value is edited by
                    // in the input by user is updated automatically.
                    $output = '';

                    // specific use case where you need to validate a specific
                    // editable column posted when you have more than one
                    // EditableColumn in the grid view. We evaluate here a
                    // check to see if buy_amount was posted for the Book model
                    // if (isset($posted['buy_amount'])) {
                    //    $output =  Yii::$app->formatter->asDecimal($model->buy_amount, 2);
                    // }

                    // similarly you can check if the name attribute was posted as well
                    // if (isset($posted['name'])) {
                    //   $output =  ''; // process as you need
                    // }
                } else {
                    $message = $model->errors;
                }

                $out = Json::encode(['output'=>'', 'message'=>$message['name']]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 3000,
                'icon' => 'fa fa-plus',
                'message' => Yii::t('app', 'Category Record has been saved.'),
                'title' => Yii::t('app', 'Add Category')
            ]);
            switch (Yii::$app->request->post('action', 'save')) {
                case 'next':
                    return $this->redirect(['create']);
                default:
                    return $this->redirect(['index']);
            }

//            return $this->redirect(['index']);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 3000,
                'icon' => 'fa fa-pencil',
                'message' => Yii::t('app', 'Category has been edited.'),
                'title' => Yii::t('app', 'Update Category')
            ]);
            return $this->redirect(['index']);
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
        if (Product::find()->where(['category_id' => $id])->all()) {
            Yii::$app->getSession()->setFlash('warning', [
                'type' => Alert::TYPE_WARNING,
                'duration' => 0,
                'icon' => 'fa fa-trash-o',
                'message' => Html::encode('Category has one or more product') . '</br>'
                    . Html::encode('Do you wish to delete all?')
                    . Html::a(Yii::t('app','Delete all'), ['delete-all', 'id' => $id]  , ['class' => 'btn btn-primary alert-link']),
                'title' => 'Delete Category'
            ]);
        } else {
            $this->findModel($id)->delete();

            Yii::$app->getSession()->setFlash('success', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 3000,
                'icon' => 'fa fa-trash-o',
                'message' => 'Category Record has been deleted.',
                'title' => 'Delete Category'
            ]);
        }

        return $this->redirect(['index']);
    }

    public function actionDeleteAll($id) {

        try {
            $transaction = \Yii::$app->db->beginTransaction();
            if ( $products = Product::find()->where(['category_id' => $id])->all()){
                foreach ($products as $product) {
                    // delete all offer where product_id = product.id
                    Offer::deleteAll('product_id = :product_id', [':product_id' => $product->id]);

                    // delete all image of product
                    Image::deleteAll('product_id = :product_id', [':product_id' => $product->id]);

                    // delelte images folder
                    $dir = Yii::getAlias('@frontend/web/uploads/products/images/' . $product->id);
                    FileHelper::removeDirectory($dir);

                    // delete wishlist
                    WishList::deleteAll('product_id = :product_id', [':product_id' => $product->id]);

                    // delete product in order details
                    OrderDetails::deleteAll('product_id = :product_id', ['product_id' => $product->id]);

                    // delete product season
                    ProductSeason::deleteAll('product_id = :product_id', ['product_id' => $product->id]);

                    // delete product
                    $product->delete();
                }
            }

            $transaction->commit();

            $this->findModel($id)->delete();

            Yii::$app->getSession()->setFlash('success', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 3000,
                'icon' => 'fa fa-trash-o',
                'message' => 'Season Record has been deleted.',
                'title' => 'Delete Season'
            ]);
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
