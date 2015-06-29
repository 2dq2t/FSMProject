<?php
namespace frontend\components;
use common\models\ProductRating;
use common\models\Rating;
use Yii;
use yii\base\Component;
use yii\db\Query;

/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 1:23 SA
 */
class CommonFunction extends Component{
   public function custom_shuffle($my_array = array()) {
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
    public function productRating($product_id){
        $rating_id = (new Query())->select('rating_id')->from('product_rating')->where(['product_id' => $product_id])->all();
        $total_score = 0;
        $count_rating = 0;
        foreach ($rating_id as $rating) {
            $count_rating++;
            $score =(new Query())->select('rating')->from('rating') ->where(['id' => $rating['rating_id']])->one();
            $total_score += $score['rating'];
        }
        if ($total_score > 0 && $count_rating > 0) {
            $rating_average = $total_score / $count_rating;
        } else
            $rating_average = 0;
        return $rating_average;
    }

    public function getProductOneImage($product_id){
        $product_image = (new Query())->select('path')->from('image')->where(['product_id' =>$product_id])->one();
        return $product_image['path'];
    }

    public function getProductOffer($product_id){
        $offer = (new Query())->select('discount,start_date,end_date')->from('offer')->where(['active'=>1,'product_id'=>$product_id])->one();
        $today = date("d-m-Y");
        $offer_start_date = date("d-m-Y",$offer['start_date']);
        $offer_end_date = date("d-m-Y",$offer['end_date']);
        if($offer_start_date <= $today && $today <= $offer_end_date) {
            $product_offer = $offer['discount'];
        }
        else
            $product_offer=0;
        return $product_offer;
    }
    public function getProductUnit($product_id){
        $unit_id = (new Query())->select('unit_id')->from('product')->where(['id'=>$product_id])->one();
        $product_unit = (new Query())->select('name')->from('unit')->where(['id'=>$unit_id['unit_id']])->one();

        return $product_unit['name'];
    }
    public function productPrice($product_price,$product_offer){
        if($product_offer != 0)
            return $product_price - ($product_price*($product_offer/100));
        else
            return $product_price;
    }
    public function sellingPrice($product_price,$product_offer){
        $selling_price = $product_price - ($product_price*($product_offer/100));
        return $selling_price;
    }
}