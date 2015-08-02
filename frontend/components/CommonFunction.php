<?php
namespace frontend\components;

use common\models\ProductRating;
use common\models\Rating;
use common\models\Voucher;
use Yii;
use yii\base\Component;
use yii\db\Query;

/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 1:23 SA
 */
class CommonFunction extends Component
{
    public function custom_shuffle($my_array = array())
    {
        $copy = array();
        while (count($my_array)) {
            // takes a rand array elements by its key
            $element = array_rand($my_array);
            // assign the array and its value to an another array
            $copy[$element] = $my_array[$element];
            //delete the element from source array
            unset($my_array[$element]);
        }
        return $copy;
    }

    public function getProductRating($product_id)
    {
        $rating_id = (new Query())->select('rating_id')->from('product_rating')->where(['product_id' => $product_id])->all();
        $total_score = 0;
        $count_rating = 0;
        foreach ($rating_id as $rating) {
            $count_rating++;
            $score = (new Query())->select('rating')->from('rating')->where(['id' => $rating['rating_id']])->one();
            $total_score += $score['rating'];
        }
        if ($total_score > 0 && $count_rating > 0) {
            $rating_average = $total_score / $count_rating;
        } else
            $rating_average = 0;
        return $rating_average;
    }

    public function getProductOneImage($product_id)
    {
        $product_image = (new Query())->select('resize_path')->from('image')->where(['product_id' => $product_id])->one();
        return $product_image['resize_path'];
    }

    public function getProductOffer($product_id)
    {
        $offer = (new Query())->select('discount,start_date,end_date')->from('offer')->where(['active' => 1, 'product_id' => $product_id])->one();
        $today = date("d-m-Y");
        $offer_start_date = date("d-m-Y", $offer['start_date']);
        $offer_end_date = date("d-m-Y", $offer['end_date']);
        if ($offer_start_date <= $today && $today <= $offer_end_date) {
            $product_offer = $offer['discount'];
        } else
            $product_offer = 0;
        return $product_offer;
    }

    public function getProductUnit($product_id)
    {
        $unit_id = (new Query())->select('unit_id')->from('product')->where(['id' => $product_id])->one();
        $product_unit = (new Query())->select('name')->from('unit')->where(['id' => $unit_id['unit_id']])->one();

        return $product_unit['name'];
    }

    public function getProductPrice($product_price, $product_offer)
    {
        if ($product_offer != 0)
            return $product_price - ($product_price * ($product_offer / 100));
        else
            return $product_price;
    }

    public function getNetAmount($product_price,$tax,$product_quantity){
        $net_amount =$product_quantity*( $product_price - ($product_price * ($tax/100)));
        return $net_amount;
    }

    public function getTaxAmount($product_price,$tax,$product_quantity){
        $tax_amount = $product_quantity * $product_price * ($tax/100);
        return $tax_amount;
    }
    public function getTotalPriceWithVoucher($voucherDiscount){
        $product_cart = Yii::$app->session->get('product_cart');
        if(empty($product_cart)){
            return 0;
        }
        else {
            $total_price = 0;
            foreach ($product_cart as $key => $item) {
                $product_price = (new Query())->select('price')->from('product')->where(['id' => $item['product_id']])->one();
                $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $item['product_quantity'];
            }
            $total_price_with_voucher = $total_price - ($total_price *($voucherDiscount/100));
            return $total_price_with_voucher;
        }
    }
    public function checkVoucher($voucher){

        $check_voucher = Voucher::find()->where(['code' => $voucher])->one();
        $json['info'] = $voucher;
        $today = date_create_from_format('d/m/Y',  date("d/m/Y")) ?
            mktime(null,null,null, date_create_from_format('d/m/Y',  date("d/m/Y"))->format('m'), date_create_from_format('d/m/Y',  date("d/m/Y"))->format('d'), date_create_from_format('d/m/Y',  date("d/m/Y"))->format('y')) : time();
        if(empty($check_voucher)){
            return false;
        } else if ($today < $check_voucher['start_date']) {
            return false;
        } else if ($today > $check_voucher['end_date']) {
            return false;
        }elseif(!empty($check_voucher['order_id'])){
            return false;
        }
        else if ($check_voucher['active'] == 1) {
            return true;
        }
        else{
            $json['error'] = Yii::t('app', 'InputVoucherMsg01');
        }
    }
}