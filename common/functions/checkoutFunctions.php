<?php
namespace common\functions;
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/08/2015
 * Time: 12:23 CH
 */
use yii\base\Component;
use yii\db\Query;
use common\models\Voucher;

class checkoutFunctions {

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
                $product_offer = Yii::$app->checkoutFunctions->getProductOffer($item['product_id']);
                $total_price += Yii::$app->checkoutFunctions->getProductPrice($product_price['price'], $product_offer) * $item['product_quantity'];
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