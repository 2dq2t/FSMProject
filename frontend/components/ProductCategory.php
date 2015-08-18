<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 06/06/2015
 * Time: 12:21 CH
 */
namespace frontend\components;
use common\models\Season;
use Yii;
use common\models\Category;
use yii\db\Query;

class ProductCategory{
    const STATUS_ACTIVE = 1;
    public function getCategory(){
        //get category in
         $categories = (new Query())->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
            ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => self::STATUS_ACTIVE])->all();
        return $categories;
    }
    public function getSeason(){
        $season = Season::find()->select(['id','name'])->where(['active'=>self::STATUS_ACTIVE])->all();
        return $season;
    }

}