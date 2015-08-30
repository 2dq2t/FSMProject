<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 05/08/2015
 * Time: 8:09 CH
 */

namespace frontend\controllers;


use common\models\Product;
use common\models\WishList;
use yii\web\Controller;
use yii;
use yii\db\Query;

class WishListController extends Controller {

    public function actionGetWishList()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('index.php?r=account/register');
        else {
            $customer_id = Yii::$app->user->identity->getId();
            $product_list = WishList::find()->select('product_id')->where(['customer_id' => $customer_id])->all();
            $wish_list_product = array();
            foreach ($product_list as $item) {
                $product_detail = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.quantity_in_stock as product_quantity', 'product.tax as product_tax', 'product.sold as product_sold', 'product.price as product_price', 'image.resize_path as product_image'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => Product::STATUS_ACTIVE, 'product.id' => $item['product_id']])->one();
                if (!empty($product_detail['product_id'])) {
                    $product_detail['product_offer'] = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                    $product_detail['product_unit'] = Yii::$app->CommonFunction->getProductUnit($item['product_id']);
                    array_push($wish_list_product, $product_detail);
                }
            }
            $product_session_id = Yii::$app->session->get('product_session');
            $product_session = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.price as product_price'
                , 'product.tax as product_tax', 'image.resize_path as product_image'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => Product::STATUS_ACTIVE, 'product.id' => $product_session_id])->groupBy('product.id')->all();
            foreach ($product_session as $key=>$item) {
                    //get product image
                    $product_image = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                    $product_session[$key]['product_image'] = $product_image;
                    //get product offer
                    $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                    $product_session[$key]['product_offer'] = $product_offer;
                    //Get rating average
                    $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                    $product_session[$key]['product_rating'] = $rating_average;

            }
            return $this->render('getWishList', [
                'wish_list_product' => $wish_list_product,
                'product_session' => $product_session,
            ]);
        }
    }

    public function actionRemoveWishList()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            try {
                $post_data = Yii::$app->request->post();
                $product_id = json_decode($post_data['product_id']);
                $customer_id = Yii::$app->user->identity->getId();
                WishList::findOne(['customer_id' => $customer_id, 'product_id' => $product_id])->delete();
                $json['success'] = Yii::t('app', 'RemoveWishListMsg01');
                $json['product_id'] = $product_id;
                $json['total'] = WishList::find()->where(['customer_id' => $customer_id])->count();
            } catch (\mysqli_sql_exception $ex) {
                $json['error'] = Yii::t('app', 'RemoveWishListMsg02');
            }
        } else {
            return $this->goHome();
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return json_encode($json);
    }

    public function actionAddWishList()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            if (Yii::$app->user->isGuest)
                $json['error'] = Yii::t('app', 'AddWishListMsg01');
            else {
                $customer_id = Yii::$app->user->identity->getId();
                $data = Yii::$app->request->post();
                $product_id = json_decode($data['product_id']);
                if (WishList::find()->where(['customer_id' => $customer_id, 'product_id' => $product_id])->exists()) {
                    $json['error'] = Yii::t('app', 'AddWishListMsg02');
                } else {
                    //save to wish list
                    try {
                        $wishList = new WishList();
                        $wishList->customer_id = $customer_id;
                        $wishList->product_id = $product_id;
                        $wishList->save();
                        $json['total'] = WishList::find()->where(['customer_id' => $customer_id])->count();
                        $json['success'] = Yii::t('app', 'AddWishListMsg03');
                    } catch (\yii\db\Exception $ex) {
                        $json['error'] = Yii::t('app', 'DbError');
                    }

                }
            }
        } else {
            return $this->goHome();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return json_encode($json);

    }


}