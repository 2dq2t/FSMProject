<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 05/08/2015
 * Time: 11:50 CH
 */

namespace frontend\controllers;


use yii\web\Controller;
use yii;
use yii\db\Query;
use common\models\Category;
use common\models\Product;
use yii\data\Pagination;

class SearchController extends Controller{

    public function actionAutoComplete($q)
    {
        $query = new Query();
//        $query->select(['product.name AS product_name, category.name AS category_name, i.resize_path','(MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . "*".'" IN BOOLEAN MODE) + MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . "*".'" IN BOOLEAN MODE)) AS score'])
//            ->from('product')
//            ->join('INNER JOIN', 'category', 'category.id = product.category_id')
//            ->join('INNER JOIN', '(
//                    SELECT product_id, resize_path
//                    FROM image
//                    GROUP BY product_id
//                ) AS i', 'i.product_id = product.id')
//            ->where('( MATCH(product.name) AGAINST ("' . mysql_real_escape_string($q) ."*". '" IN BOOLEAN MODE) OR ' .
//                'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) ."*". '" IN BOOLEAN MODE)) AND category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE)
//            ->having('score > 0.1')
//            ->orderBy('score DESC')
//            ->limit(10);
        $command = Yii::$app->db->createCommand("CALL search_autocomplete('" . mysql_real_escape_string($q) . "')");
        $products = $command->queryAll();
        $out = [];
        foreach ($products as $product) {
            $out[] = $product;
        }

        echo json_encode($out);
    }

    public function actionPrefetch()
    {
//        $query = new Query();
//        $query->select('product.name AS product_name, category.name AS category_name, i.resize_path')
//            ->from('product')
//            ->join('INNER JOIN', 'category', 'category.id = product.category_id')
//            ->join('INNER JOIN', '(
//                    SELECT product_id, resize_path
//                    FROM image
//                    GROUP BY product_id
//                ) AS i', 'i.product_id = product.id')
//            ->where('category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE)
//            ->limit(10);
        $command = Yii::$app->db->createCommand("CALL search_prefetch()");
        $products = $command->queryAll();
        $out = [];
        foreach ($products as $product) {
            $out[] = $product;
        }

        echo json_encode($out);
    }

    public function actionSearchResult()
    {
        $search_product = null;
        $q = null;
        $pagination = null;
        $search_with_description = null;
        if (Yii::$app->request->isGet) {
            if (empty($_GET['q']))
                return $this->goHome();
            else {
                $q = preg_replace('/([^\pL\ \ ]+)/u', '', strip_tags($_GET['q']));
                if(trim($q) == ""){
                    return $this->goHome();
                }
                if (!(empty($_GET['sort']) && empty($_GET['order']))) {
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if ($order == 'ASC')
                        $order = SORT_ASC;
                    else
                        $order = SORT_DESC;
                    $query = (new Query())
                        ->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price', 'product.tax as product_tax', 'image.resize_path as image_path','(MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . "*".'" IN BOOLEAN MODE) + MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . "*".'" IN BOOLEAN MODE)) AS score'])
                        ->from('product')
                        ->join('INNER JOIN', 'image', 'product.id = image.product_id')
                        ->join('INNER JOIN', 'category', 'product.category_id = category.id')
                        ->where('category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                            'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q)."*" . '" IN BOOLEAN MODE) OR ' .
                            'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) ."*". '" IN BOOLEAN MODE)
                         )')
                        ->having('score > 0.1')
                        ->groupBy('product.id')
                        ->orderBy(['product.' . $sort => $order]);
                    $countQuery = clone $query;
                    $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                    $search_product = $query->offset($pagination->offset)->limit($pagination->limit)->all();
                    $search_with_description = 'checked';
                } else {
                    $query = (new Query())
                        ->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price', 'product.tax as product_tax','category.name', 'image.path as image_path', '(MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . "*".'" IN BOOLEAN MODE) + MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . "*".'" IN BOOLEAN MODE)) AS score'])
                        ->from('product')
                        ->innerJoin('image', 'product.id = image.product_id')
                        ->innerJoin('category', 'product.category_id = category.id')
                        ->where('category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                            'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) ."*". '" IN BOOLEAN MODE) OR ' .
                            'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) ."*". '" IN BOOLEAN MODE)
                         )')
                        ->having('score > 0.1')
                        ->groupBy('product.id')
                        ->orderBy('score DESC');
                    $countQuery = clone $query;
                    $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                    $search_product = $query->offset($pagination->offset)->limit($pagination->limit)->all();
                    foreach ($search_product as $key => $item) {
                        $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                        $search_product[$key]['product_rating'] = $rating_average;
                        $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                        $search_product[$key]['product_offer'] = $product_offer;
                    }
                    $search_with_description = 'checked';
                }
            }
        }


        return $this->render('searchResult', ['products' => $search_product, 'pagination' => $pagination, 'q' => $q, 'search_with_description' => $search_with_description]);
    }
}