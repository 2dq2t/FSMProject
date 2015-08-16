<?php
namespace frontend\components;

use common\models\Offer;
use common\models\Product;
use common\models\ProductRating;
use common\models\Rating;
use common\models\Image;
use common\models\Unit;
use common\models\WishList;
use Yii;

/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 1:23 SA
 */
class CommonFunction
{
    const STATUS_ACTIVE = 1;
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
        $rating_id = ProductRating::find()->select(['rating_id'])->where(['product_id' => $product_id])->all();
        $total_score = 0;
        $count_rating = 0;
        foreach ($rating_id as $rating) {
            $count_rating++;
            $score = Rating::find()->select(['rating'])->where(['id' => $rating['rating_id']])->one();
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
        $product_image = Image::find()->select(['path'])->where(['product_id' => $product_id])->one();
        return $product_image['path'];
    }

    public function getProductOffer($product_id)
    {
        $offer = Offer::find()->select(['discount','start_date','end_date'])->where(['active' => self::STATUS_ACTIVE, 'product_id' => $product_id])->one();
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

        $unit_id =  Product::find()->select(['unit_id'])->where(['id' => $product_id])->one();

        $product_unit =  Unit::find()->select(['name'])->where(['id' => $unit_id['unit_id']])->one();

        return $product_unit['name'];
    }

    public function getProductPrice($product_price, $product_offer)
    {
        if ($product_offer != 0)
            return $product_price - ($product_price * ($product_offer / 100));
        else
            return $product_price;
    }

    public function  getNumberProductWishList($customer_id)
    {
        $wish_list_product = WishList::find()->select(['product_id'])->where(['customer_id' => $customer_id])->all();
        $count = 0;
        foreach($wish_list_product as $item){
            $product = Product::find()->select(['id'])->from('product')->where(['id'=>$item['product_id'],'active'=>self::STATUS_ACTIVE])->one();
            if(!empty($product['id'])){
                $count++;
            }
        }
        return $count;
    }
}