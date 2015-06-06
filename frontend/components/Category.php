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
class Category extends Component{
    public function category(){
        //get category in
        $categories =(new Query())->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
            ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => 1])->all();
        return $categories;
    }
}