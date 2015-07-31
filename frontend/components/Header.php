<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 12:35 CH
 */
namespace frontend\components;

use common\models\WishList;
use Yii;
use yii\base\Component;
use yii\db\Query;

class Header extends Component
{

    public function  getNumberProductWishList($customer_id)
    {
        $number_product = WishList::find()->where(['customer_id' => $customer_id])->count();
        return $number_product;
    }

    public function getCartInfo()
    {
        $product_cart = Yii::$app->session->get('product_cart');
        $total_price = 0;
        $total_product = 0;
        $cart_info = array();
        if (!empty($product_cart)) {
            foreach ($product_cart as $key => $item) {
                $product_price = (new Query())->select('price')->from('product')->where(['id' => $item['product_id']])->one();
                $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $item['product_quantity'];
                $total_product += $item['product_quantity'];
                $product_cart[$key]['product_price'] = Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer);
                $product_cart[$key]['product_image'] = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $product_name = (new Query())->select('name')->from('product')->where(['id' => $item['product_id'], 'active' => 1])->one();
                $product_cart[$key]['product_name'] = $product_name['name'];
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