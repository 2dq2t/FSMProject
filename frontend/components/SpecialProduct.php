<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 12:21 CH
 */
namespace frontend\components;
use Yii;
use yii\base\Component;
use yii\db\Query;
class SpecialProduct extends Component{
    public function specialProduct(){

        //get special product - product has offer
        $special_product = array();
        $special_query = (new Query())->select(['product.id as product_id','product.name as product_name','product.price as product_price','product.tax as product_tax',
            'offer.discount as product_offer','start_date as offer_start_date','end_date as offer_end_date'])->from('product')->innerJoin('offer','product.id = offer.product_id')->where(['product.active'=>1,'offer.active'=>1])->orderBy(['offer.discount'=>SORT_DESC])->limit(3)->all();
        $today = date("d-m-Y");
        foreach($special_query as $special) {
            $offer_start_date = date("d-m-Y", $special['offer_start_date']);
            $offer_end_date = date("d-m-Y", $special['offer_end_date']);
            if ($offer_start_date <= $today && $today <= $offer_end_date) {
                //get product image
                $product_image = Yii::$app->CommonFunction->getProductOneImage($special['product_id']);
                $special['product_image'] = $product_image;
                //Get rating average
                $rating_average = Yii::$app->CommonFunction->productRating($special['product_id']);
                $special['product_rating'] = $rating_average;
                array_push($special_product,$special);
            }
        }
        return $special_product;
    }
}