<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/08/2015
 * Time: 11:03 SA
 */

namespace frontend\controllers;


use yii\web\Controller;
use Yii;
use common\models\ProductRating;
use common\models\Product;
use common\models\Rating;
use common\models\Image;
use yii\db\Query;
use yii\data\Pagination;

class ProductController extends Controller{

    public function actionRating()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            if (Yii::$app->user->isGuest)
                $json['error'] = Yii::t('app', 'RatingProductMsg01');
            else {
                $postData = Yii::$app->request->post();
                if (isset($postData['product_id'])) {
                    $product_id = $postData['product_id'];
                } else {
                    $product_id = 0;
                }
                $check_exist_rating = ProductRating::find()->where(['product_id' => $product_id, 'customer_id' => Yii::$app->user->identity->getId()])->one();
                if (!empty($check_exist_rating['rating_id'])) {

                    $json['error'] = Yii::t('app', 'RatingProductMsg02');
                } else {
                    if (Product::find()->where(['id' => $product_id, 'active' => 1])->exists()) {
                        if (isset($postData['score'])) {
                            $score = $postData['score'];
                        } else
                            $score = 5;
                        $rating_info = Rating::find()->where(['rating' => $score])->one();
                        try {
                            $product_rating = new ProductRating();
                            $product_rating->product_id = $product_id;
                            $product_rating->rating_id = $rating_info['id'];
                            $product_rating->customer_id = Yii::$app->user->identity->getId();
                            $product_rating->save();
                            $json['success'] = Yii::t('app', 'RatingProductMsg03') . $score . Yii::t('app', 'RatingProductMsg04');
                        } catch (\yii\db\Exception $connection) {
                            $json['error'] = "Lỗi kết nối! bạn vui lòng thử lại sau ít phút";
                        }
                    } else {
                        $json['error'] = Yii::t('app', 'DbError');
                    }
                }
            }
        } else {
            return $this->goHome();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [json_encode($json)];
    }

    public function actionViewDetail()
    {


        if (empty($_GET['product']))
            return $this->goHome();
        $productName = $_GET['product'];

        //Get product detail
        $product_detail = Product::find()->where(['name' => $productName])->one();

        //Get rating average
        $rating_average = Yii::$app->CommonFunction->getProductRating($product_detail['id']);

        //get product offer
        $product_offer = Yii::$app->CommonFunction->getProductOffer($product_detail['id']);

        //get product image
        $product_image_detail = Image::find()->where(['product_id' => $product_detail['id']])->all();

        //get product unit
        $product_unit = (new Query())->select('name')->from('unit')->where(['id' => $product_detail['unit_id']])->one();

        //get product tag
        $product_tag = (new Query())->select('name')->from('tag')->innerJoin('product_tag', 'tag.id = product_tag.tag_id')->where(['product_tag.product_id' => $product_detail['id']])->all();

        //get product in same category
        $products_same_category = array();
        $products_category = (new Query())->select(['id as product_id', 'name as product_name', 'price as product_price', 'tax as product_tax'])->from('product')->where(['active' => '1', 'category_id' => $product_detail['category_id']])->all();
        foreach ($products_category as $item) {
            if (!($item['product_id'] == $product_detail['id'])) {
                //get product image
                $product_category_image = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $item['product_image'] = $product_category_image;
                //get product offer
                $product_category_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $item['product_offer'] = $product_category_offer;

                //Get rating average
                $rating_category_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                $item['product_rating'] = $rating_category_average;

                array_push($products_same_category, $item);
            }
        }
        //set product recent view
        $flag = true;
        $product = $product_detail['id'];
        $product_session = Yii::$app->session->get('product_session');
        if (count($product_session) == 0) {
            Yii::$app->session->set('product_session', [$product]);
        } else {
            foreach ($product_session as $item) {
                if (($item == $product)) {
                    $flag = false;
                }
            }
            if ($flag) {
                array_push($product_session, $product);
                Yii::$app->session->set('product_session', $product_session);
            }
        }

        return $this->render('viewDetail', [
            'product_detail' => $product_detail, 'product_image_detail' => $product_image_detail,
            'product_offer' => $product_offer, 'rating_average' => $rating_average,
            'product_unit' => $product_unit, 'product_tag' => $product_tag,
            'products_same_category' => $products_same_category,
        ]);
    }

    public function actionGetProductSeason()
    {

    }
    public function actionGetProductCategory()
    {
        if (Yii::$app->request->isGet) {

            if (empty($_GET['category']))
                return $this->goHome();
            else {
                $category_name = $_GET['category'];
                $category_ID = (new Query())->select('id')->from('category')->where(['name' => $category_name])->one();
                if (!(empty($_GET['sort']) && empty($_GET['order']))) {
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if ($order == 'ASC')
                        $order = SORT_ASC;
                    else
                        $order = SORT_DESC;
                    $category_product_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $category_ID['id']])->groupBy('product.id')->orderBy(['product.' . $sort => $order]);

                } else {
                    $category_product_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $category_ID['id']])->groupBy('product.id');
                }
                $countQuery = clone $category_product_query;
                $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                $category_product = $category_product_query->offset($pagination->offset)->limit($pagination->limit)->all();
                foreach ($category_product as $key => $item) {
                    $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                    $category_product[$key]['product_rating'] = $rating_average;
                    $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                    $category_product[$key]['product_offer'] = $product_offer;
                }
            }


            return $this->render('getProductCategory', [
                'category_name' => $category_name, 'category_product' => $category_product, 'pagination' => $pagination
            ]);
        }
    }

    public function actionGetProductTag()
    {
        if (Yii::$app->request->isGet) {
            if (!empty($_GET['tag'])) {
                $list_id = array();
                $tag_id = (new Query())->select(['id'])->from('tag')->where(['name' => $_GET['tag']])->one();
                $product_id = (new Query())->select(['product_id'])->from('product_tag')->where(['tag_id' => $tag_id['id']])->all();
                foreach ($product_id as $key) {
                    array_push($list_id, $key['product_id']);
                }
                if (!(empty($_GET['sort']) && empty($_GET['order']))) {
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if ($order == 'ASC')
                        $order = SORT_ASC;
                    else
                        $order = SORT_DESC;
                    $product_tag_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.id' => $list_id])->groupBy('product.id')->orderBy(['product.' . $sort => $order]);

                } else {
                    $product_tag_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.id' => $list_id])->groupBy('product.id');
                }

                $countQuery = clone $product_tag_query;
                $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                $product_tag = $product_tag_query->offset($pagination->offset)->limit($pagination->limit)->all();
                foreach ($product_tag as $key => $item) {
                    $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                    $product_tag[$key]['product_rating'] = $rating_average;
                    $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                    $product_tag[$key]['product_offer'] = $product_offer;
                }

                return $this->render('getProductTag', ['product_tag' => $product_tag, 'pagination' => $pagination]);
            }
        }
    }
}