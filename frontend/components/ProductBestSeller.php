<?php
namespace frontend\components;
use Yii;
use yii\base\Component;
use yii\db\Query;
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 12:10 CH
 */
class ProductBestSeller extends Component
{
    public function getProductBestSeller()
    {
        //get bestseller product
        $best_seller_on_tag = array();
        $tag_id = (new Query())->select('id')->from('tag')->where(['name' => 'bestseller'])->one();
        if (!empty($tag_id['id'])) {
            $best_seller_on_tag = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.price as product_price', 'product.tax as product_tax'])->from('product')->innerJoin('product_tag', 'product.id = product_tag.product_id')->where(['product.active' => 1, 'product_tag.tag_id' => $tag_id['id']])->all();
        }
        $best_seller_on_system = (new Query())->select(['id as product_id', 'name as product_name', 'price as product_price', 'tax as product_tax', 'sold as product_sold'])->from('product')->where(['active' => '1'])->orderBy(['sold' => SORT_DESC])->limit(3)->all();
        //get product's image, product offer, product rating and loại bỏ giá trị trùng nhau with bestseller product
        if (!empty($best_seller_on_tag[0]['product_id'])) {
            foreach ($best_seller_on_system as $key => $item) {
                //get product image
                $product_image = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $best_seller_on_system[$key]['product_image'] = $product_image;
                //get product offer
                $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $best_seller_on_system[$key]['product_offer'] = $product_offer;
                //Get rating average
                $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                $best_seller_on_system[$key]['product_rating'] = $rating_average;

                foreach ($best_seller_on_tag as $best_seller_key => $best_seller_item) {
                    //loại bỏ trùng product
                    if ($item['product_id'] == $best_seller_item['product_id']) {
                        unset($best_seller_on_tag[$best_seller_key]);
                    } else {
                        //get product image
                        $product_image = Yii::$app->CommonFunction->getProductOneImage($best_seller_item['product_id']);
                        $best_seller_on_tag[$best_seller_key]['product_image'] = $product_image;
                        //get product offer
                        $product_offer = Yii::$app->CommonFunction->getProductOffer($best_seller_item['product_id']);
                        $best_seller_on_tag[$best_seller_key]['product_offer'] = $product_offer;

                        //Get rating average
                        $rating_average = Yii::$app->CommonFunction->getProductRating($best_seller_item['product_id']);
                        $best_seller_on_tag[$best_seller_key]['product_rating'] = $rating_average;
                    }
                }
            }
        } else {
            foreach ($best_seller_on_system as $key => $item) {
                //get product image
                $product_image =  Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $best_seller_on_system[$key]['product_image'] = $product_image;
                //get product offer
                $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $best_seller_on_system[$key]['product_offer'] = $product_offer;
                //Get rating average
                $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                $best_seller_on_system[$key]['product_rating'] = $rating_average;
            }
        }
        $best_seller = array_merge($best_seller_on_tag, $best_seller_on_system);
        $best_seller = Yii::$app->CommonFunction->custom_shuffle($best_seller);

        return $best_seller;
    }
}