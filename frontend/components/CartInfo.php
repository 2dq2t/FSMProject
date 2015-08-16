<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 12:35 CH
 */
namespace frontend\components;


use common\models\Product;
use Yii;

class CartInfo
{
    public function getCartInfo()
    {
        $product_cart = Yii::$app->session->get('product_cart');
        $total_price = 0;
        $total_product = 0;
        $cart_info = array();
        if (!empty($product_cart)) {
            foreach ($product_cart as $key => $item) {
                $product = Product::find()->select(['price','name'])->where(['id' => $item['product_id']])->one();
                $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $total_price += Yii::$app->CommonFunction->getProductPrice($product['price'], $product_offer) * $item['product_quantity'];
                $total_product += $item['product_quantity'];
                $product_cart[$key]['product_price'] = Yii::$app->CommonFunction->getProductPrice($product['price'], $product_offer);
                $product_cart[$key]['product_image'] = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $product_cart[$key]['product_name'] = $product['name'];
            }

            $cart_info['total_product'] = $total_product;
            $cart_info['total_price'] = $total_price;
            $cart_info['product_in_cart'] = $product_cart;
        } else {
            $cart_info['total_product'] = $total_product;
            $cart_info['total_price'] = $total_price;
            $cart_info['product_in_cart'] = null;
        }
        return $cart_info;
    }
}