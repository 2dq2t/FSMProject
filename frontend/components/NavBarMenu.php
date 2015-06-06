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
class NavBarMenu extends Component{
    public function navBar(){
        //get category in navbar
        $navBar =(new Query())->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
            ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => 1])->all();
        return $navBar;
    }
    public function  numberProductWishList($customer_id){
        $number_product = WishList::find()->where(['customer_id'=>$customer_id])->count();
        return $number_product;
    }
}