<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Customer;
use common\models\Image;
use common\models\Product;
use common\models\ProductRating;
use common\models\ProductSeason;
use common\models\Rating;
use common\models\Season;
use common\models\Voucher;
use common\models\WishList;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\alert\Alert;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        //get new product
        $new_product_from_tag = array();
        $tag_id = (new Query())->select('id')->from('tag')->where(['name' => 'new'])->one();
        if (!empty($tag_id['id'])) {
            $new_product_from_tag = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.price as product_price', 'product.tax as product_tax'])->from('product')->innerJoin('product_tag', 'product.id = product_tag.product_id')->where(['product.active' => 1, 'product_tag.tag_id' => $tag_id['id']])->all();

        }
        $new_product_from_system = (new Query())->select(['id as product_id', 'name as product_name', 'price as product_price', 'tax as product_tax'])->from('product')->where(['active' => '1'])->orderBy(['id' => SORT_DESC])->limit(10)->all();
        //get product's image, product offer, product rating and loại bỏ giá trị trùng nhau with new product
        if (!empty($new_product_from_tag[0]['product_id'])) {
            foreach ($new_product_from_system as $key => $item) {
                //get product image
                $product_image = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $new_product_from_system[$key]['product_image'] = $product_image;
                //get product offer
                $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $new_product_from_system[$key]['product_offer'] = $product_offer;
                //Get rating average
                $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                $new_product_from_system[$key]['product_rating'] = $rating_average;

                foreach ($new_product_from_tag as $new_product_key => $new_product_item) {
                    //loại bỏ trùng product
                    if ($item['product_id'] == $new_product_item['product_id']) {
                        unset($new_product_from_tag[$new_product_key]);
                    } else {
                        //get product image
                        $product_image = Yii::$app->CommonFunction->getProductOneImage($new_product_item['product_id']);
                        $new_product_from_tag[$new_product_key]['product_image'] = $product_image;
                        //get product offer
                        $product_offer = Yii::$app->CommonFunction->getProductOffer($new_product_item['product_id']);
                        $new_product_from_tag[$new_product_key]['product_offer'] = $product_offer;

                        //Get rating average
                        $rating_average = Yii::$app->CommonFunction->getProductRating($new_product_item['product_id']);
                        $new_product_from_tag[$new_product_key]['product_rating'] = $rating_average;
                    }
                }
            }
        } else {
            foreach ($new_product_from_system as $key => $item) {
                //get product image
                $product_image = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $new_product_from_system[$key]['product_image'] = $product_image;
                //get product offer
                $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $new_product_from_system[$key]['product_offer'] = $product_offer;
                //Get rating average
                $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                $new_product_from_system[$key]['product_rating'] = $rating_average;
            }
        }
        $new_product = array_merge($new_product_from_tag, $new_product_from_system);
        $new_product = Yii::$app->CommonFunction->custom_shuffle($new_product);

        //get product from season
        //check now season is?
        $product_season = array();
        $season = Season::find()->all();
        $season_id = null;
        foreach ($season as $season_item) {
            $season_from = date("d-m", $season_item['from']);
            $season_to = date("d-m", $season_item['to']);
            $today = date("m-d");
            if ($season_from <= $today && $today <= $season_to) {
                $product_id = ProductSeason::find()->where(['season_id' => $season_item['id']])->all();
                if (!empty($product_id[0]['season_id'])) {
                    foreach ($product_id as $product_item) {
                        $product = (new Query())->select(['id as product_id', 'name as product_name', 'price as product_price', 'tax as product_tax'])->from('product')->where(['active' => '1', 'id' => $product_item['product_id']])->one();
                        if (!empty($product['product_id'])) {
                            //get product image
                            $product_image = Yii::$app->CommonFunction->getProductOneImage($product_item['product_id']);
                            $product['product_image'] = $product_image;
                            //get product offer
                            $product_offer = Yii::$app->CommonFunction->getProductOffer($product_item['product_id']);
                            $product['product_offer'] = $product_offer;

                            //Get rating average
                            $rating_average = Yii::$app->CommonFunction->getProductRating($product_item['product_id']);
                            $product['product_rating'] = $rating_average;

                            array_push($product_season, $product);
                            $product = null;
                        }
                    }
                }
            }
        }

        $product_season = Yii::$app->CommonFunction->custom_shuffle($product_season);

        //get slide image
        $slide_show = (new Query())->select(['slide_show.id as slide_show_id', 'slide_show.path as slide_show_path', 'product.name as product_name'])->from('slide_show')->leftJoin('product', 'slide_show.product_id = product.id')->where(['slide_show.active' => 1])->all();

        //get number wishlist
        /* $number_product = WishList::find()->where(['customer_id'=>Yii::$app->user->identity->getId()])->count();
         echo $number_product;*/
        return $this->render('index', [
            'slide_show' => $slide_show, 'new_product' => $new_product,
            'product_season' => $product_season,
        ]);
    }

    public function actionAutoComplete($q)
    {
        $query = new Query();
        $query->select('product.name AS product_name, category.name AS category_name, i.resize_path')
            ->from('product')
            ->join('INNER JOIN', 'category', 'category.id = product.category_id')
            ->join('INNER JOIN', '(
                    SELECT product_id, resize_path
                    FROM image
                    GROUP BY product_id
                ) AS i', 'i.product_id = product.id')
            ->where('MATCH(product.name) AGAINST ("+' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) AND category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE)
            ->limit(10);
        $command = $query->createCommand();
        $products = $command->queryAll();
        $out = [];
        foreach ($products as $product) {
            $out[] = $product;
        }

        echo json_encode($out);
    }

    public function actionPrefetch()
    {
        $query = new Query();
        $query->select('product.name AS product_name, category.name AS category_name, i.resize_path')
            ->from('product')
            ->join('INNER JOIN', 'category', 'category.id = product.category_id')
            ->join('INNER JOIN', '(
                    SELECT product_id, resize_path
                    FROM image
                    GROUP BY product_id
                ) AS i', 'i.product_id = product.id')
            ->where('category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE)
            ->limit(10);
        $command = $query->createCommand();
        $products = $command->queryAll();
        $out = [];
        foreach ($products as $product) {
            $out[] = $product;
        }

        echo json_encode($out);
    }

    public function actionSearch()
    {
        $search_product = null;
        $q = null;
        $pagination = null;
        $search_with_description = null;
        if (Yii::$app->request->isGet) {
            if (empty($_GET['q']))
                return $this->goHome();
            else {
                $q = $_GET['q'];
                if (!(empty($_GET['sort']) && empty($_GET['order']))) {
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if ($order == 'ASC')
                        $order = SORT_ASC;
                    else
                        $order = SORT_DESC;
                    $query = (new Query())
                        ->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price', 'product.tax as product_tax', 'image.resize_path as image_path'])
                        ->from('product')
                        ->join('INNER JOIN', 'image', 'product.id = image.product_id')
                        ->join('INNER JOIN', 'category', 'product.category_id = category.id')
                        ->where('category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                            'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                            'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                            'MATCH(product.description) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE)
                         )')
                        ->groupBy('product.id')
                        ->orderBy(['product.' . $sort => $order]);
                    $countQuery = clone $query;
                    $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                    $search_product = $query->offset($pagination->offset)->limit($pagination->limit)->all();
                    $search_with_description = 'checked';
                } else {
                    $query = (new Query())
                        ->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price', 'product.tax as product_tax', 'image.resize_path as image_path'])
                        ->from('product')
                        ->innerJoin('image', 'product.id = image.product_id')
                        ->innerJoin('category', 'product.category_id = category.id')
                        ->where('category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                            'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                            'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                            'MATCH(product.description) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE)
                         )')
                        ->groupBy('product.id');
                    $countQuery = clone $query;
                    $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                    $search_product = $query->offset($pagination->offset)->limit($pagination->limit)->all();
                    $search_with_description = 'checked';
                }
            }
        }
        if (Yii::$app->request->post()) {
            if (empty($_POST['search-key']) && empty($_POST['search-option']))
                return $this->goHome();

            $q = $_POST['search-key'];
            $search_option = $_POST['search-option'];
            if (!empty($_POST['description'])) {
                if ($search_option == 'all') {
                    $where_condition = 'category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                        'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                        'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                        'MATCH(product.description) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE)
                         )';
                } elseif ($search_option == 'name') {
                    $where_condition = 'category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                        'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                        'MATCH(product.description) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE)
                         )';
                } else {
                    $where_condition = 'category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                        'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                        'MATCH(product.description) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE)
                         )';
                }
                $search_with_description = 'checked';
            } else {
                if ($search_option == 'all') {
                    $where_condition = 'category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                        'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                        'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE))';
                } elseif ($search_option == 'name') {
                    $where_condition = 'category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                        'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE))';
                } else {
                    $where_condition = 'category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                        'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE))';
                }
                $search_with_description = '';
            }
            $query = (new Query())
                ->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price', 'product.tax as product_tax', 'image.resize_path as image_path'])
                ->from('product')
                ->join('INNER JOIN', 'image', 'product.id = image.product_id')
                ->join('INNER JOIN', 'category', 'product.category_id = category.id')
                ->where($where_condition)
                ->groupBy('product.id');
            $countQuery = clone $query;
            $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
            $search_product = $query->offset($pagination->offset)->limit($pagination->limit)->all();
        }

        return $this->render('search', ['products' => $search_product, 'pagination' => $pagination, 'q' => $q, 'search_with_description' => $search_with_description]);
    }

    public function actionGetProductCategory()
    {
        if (Yii::$app->request->isGet) {

            if (empty($_GET['category']))
                return $this->goHome();
            else {
                $category_name = $_GET['category'];
                $category_ID = (new Query())->select('id')->from('category')->where(['name' => $category_name])->one();
                if (!(empty($_GET['sort']) && empty($_GET['order']))) {
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if ($order == 'ASC')
                        $order = SORT_ASC;
                    else
                        $order = SORT_DESC;
                    $category_product_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path','rating.rating as product_rating'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $category_ID['id']])->groupBy('product.id')->orderBy(['product.' . $sort => $order]);

                } else {
                    $category_product_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $category_ID['id']])->groupBy('product.id');
                }
                $countQuery = clone $category_product_query;
                $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                $category_product = $category_product_query->offset($pagination->offset)->limit($pagination->limit)->all();
                foreach($category_product as $key=>$item){
                    $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                    $category_product[$key]['product_rating'] = $rating_average;
                }
            }


            return $this->render('getProductCategory', [
                'category_name' => $category_name, 'category_product' => $category_product, 'pagination' => $pagination
            ]);
        }
    }
    public function actionGetProductTag(){
        if(Yii::$app->request->isGet){
            if(!empty($_GET['tag'])){
                $list_id = array();
                $tag_id = (new Query())->select(['id'])->from('tag')->where(['name'=>$_GET['tag']])->one();
                $product_id = (new Query())->select(['product_id'])->from('product_tag')->where(['tag_id'=>$tag_id['id']])->all();
                foreach($product_id as $key){
                    array_push($list_id,$key['product_id']);
                }
                if (!(empty($_GET['sort']) && empty($_GET['order']))) {
                    $sort = $_GET['sort'];
                    $order = $_GET['order'];
                    if ($order == 'ASC')
                        $order = SORT_ASC;
                    else
                        $order = SORT_DESC;
                    $product_tag_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.id' => $list_id])->groupBy('product.id')->orderBy(['product.' . $sort => $order]);

                }
                else {
                    $product_tag_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.resize_path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.id' => $list_id])->groupBy('product.id');
                }

                $countQuery = clone $product_tag_query;
                $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
                $product_tag = $product_tag_query->offset($pagination->offset)->limit($pagination->limit)->all();
                foreach($product_tag as $key=>$item){
                    $rating_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                    $product_tag[$key]['product_rating'] = $rating_average;
                }

                return $this->render('getProductTag',['product_tag'=>$product_tag,'pagination'=>$pagination]);
            }
        }
    }
    public function actionViewDetail()
    {
        if (Yii::$app->request->isGet) {

            if (empty($_GET['product']))
                return $this->goHome();
            $productName = $_GET['product'];

            //Get product detail
            $product_detail = Product::find()->where(['name' => $productName])->one();

            //Get rating average
            $rating_average = Yii::$app->CommonFunction->getProductRating($product_detail['id']);

            //get product offer
            $product_offer = Yii::$app->CommonFunction->getProductOffer($product_detail['id']);

            //get product image
            $product_image_detail = Image::find()->where(['product_id' => $product_detail['id']])->all();

            //get product unit
            $product_unit = (new Query())->select('name')->from('unit')->where(['id' => $product_detail['unit_id']])->one();

            //get product tag
            $product_tag = (new Query())->select('name')->from('tag')->innerJoin('product_tag', 'tag.id = product_tag.tag_id')->where(['product_tag.product_id' => $product_detail['id']])->all();

            //get product in same category
            $products_same_category = array();
            $products_category = (new Query())->select(['id as product_id', 'name as product_name', 'price as product_price', 'tax as product_tax'])->from('product')->where(['active' => '1', 'category_id' => $product_detail['category_id']])->all();
            foreach ($products_category as $item) {
                if (!($item['product_id'] == $product_detail['id'])) {
                    //get product image
                    $product_category_image = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                    $item['product_image'] = $product_category_image;
                    //get product offer
                    $product_category_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                    $item['product_offer'] = $product_category_offer;

                    //Get rating average
                    $rating_category_average = Yii::$app->CommonFunction->getProductRating($item['product_id']);
                    $item['product_rating'] = $rating_category_average;

                    array_push($products_same_category, $item);
                }
            }
            //set product recent view
            $flag = true;
            $product = $product_detail['id'];
            $product_session = Yii::$app->session->get('product_session');
            if (count($product_session) == 0) {
                Yii::$app->session->set('product_session', [$product]);
            } else {
                foreach ($product_session as $item) {
                    if (($item == $product)) {
                        $flag = false;
                    }
                }
                if ($flag) {
                    array_push($product_session, $product);
                    Yii::$app->session->set('product_session', $product_session);
                }
            }

            return $this->render('viewDetail', [
                'product_detail' => $product_detail, 'product_image_detail' => $product_image_detail,
                'product_offer' => $product_offer, 'rating_average' => $rating_average,
                'product_unit' => $product_unit, 'product_tag' => $product_tag,
                'products_same_category' => $products_same_category,
            ]);
        }
    }

    public function actionGetWishList()
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();
        else {
            $customer_id = Yii::$app->user->identity->getId();
            $product_id = (new Query())->select('product_id')->from('wish_list')->where(['customer_id' => $customer_id])->all();
            $wish_list_product = array();
            foreach ($product_id as $item) {
                $product_detail = (new Query())->select(['id as product_id', 'name as product_name', 'quantity_in_stock as product_quantity', 'tax as product_tax', 'sold as product_sold', 'price as product_price'])->from('product')->where(['active' => 1, 'id' => $item['product_id']])->one();
                $product_detail['product_image'] = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                $product_detail['product_offer'] = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                $product_detail['product_unit'] = Yii::$app->CommonFunction->getProductUnit($item['product_id']);
                array_push($wish_list_product, $product_detail);
            }
            $product_session_id = Yii::$app->session->get('product_session');
            $product_session = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.price as product_price'
                , 'product.tax as product_tax', 'image.resize_path as product_image'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.id' => $product_session_id])->groupBy('product.id')->all();

            return $this->render('getWishList', [
                'wish_list_product' => $wish_list_product,
                'product_session' => $product_session,
            ]);
        }
    }

    public function actionRemoveWishList()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            try {
                $post_data = Yii::$app->request->post();
                $product_id = json_decode($post_data['product_id']);
                $customer_id = Yii::$app->user->identity->getId();
                WishList::findOne(['customer_id' => $customer_id, 'product_id' => $product_id])->delete();
                $json['success'] = Yii::t('app', 'RemoveWishListMsg01');
                $json['product_id'] = $product_id;
                $json['total'] = WishList::find()->where(['customer_id' => $customer_id])->count();
            } catch (\mysqli_sql_exception $ex) {
                $json['error'] = Yii::t('app', 'RemoveWishListMsg02');
            }
        }
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }
    }

    public function actionAddWishList()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            if (Yii::$app->user->isGuest)
                $json['info'] = Yii::t('app', 'AddWishListMsg01');
            else {
                $customer_id = Yii::$app->user->identity->getId();
                $data = Yii::$app->request->post();
                $product_id = json_decode($data['product_id']);
                if (WishList::find()->where(['customer_id' => $customer_id, 'product_id' => $product_id])->exists()) {
                    $json['info'] = Yii::t('app', 'AddWishListMsg02');
                } else {
                    //save to wish list
                    try {
                        $wishList = new WishList();
                        $wishList->customer_id = $customer_id;
                        $wishList->product_id = $product_id;
                        $wishList->save();
                        $json['total'] = WishList::find()->where(['customer_id' => $customer_id])->count();
                        $json['success'] = Yii::t('app', 'AddWishListMsg03');
                    }catch (\yii\db\Exception $ex){
                        $json['info'] ="Lỗi kết nối! bạn vui lòng thử lại sau ít phút";
                    }

                }
            }
        } else
            $json['info'] = Yii::t('app', 'AddWishListMsg04');
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }

    }

    public function actionAddToCart()
    {
        $json = array();
        $flag = true;
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            if (isset($post_data['product_id'])) {
                $product_id = $post_data['product_id'];
            } else {
                $product_id = 0;
            }
            if ($product_id == 0) {
                $json['error'] = Yii::t('app', 'AddtoCartMsg01');
            } else {
                if (Product::find()->where(['id' => $product_id, 'active' => 1])->exists()) {
                    $total_price = 0;
                    $total_product = 0;
                    if (isset($post_data['quantity'])) {
                        $product_quantity = $post_data['quantity'];
                    } else {
                        $product_quantity = 1;
                    }
                    $product_cart = Yii::$app->session->get('product_cart');
                    $product['product_id'] = $product_id;
                    $product['product_quantity'] = $product_quantity;
                    $product_price = (new Query())->select('price')->from('product')->where(['id' => $product_id])->one();
                    $product_offer = Yii::$app->CommonFunction->getProductOffer($product_id);
                    $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $product_quantity;
                    $total_product += $product_quantity;
                    if (count($product_cart) == 0) {
                        Yii::$app->session->set('product_cart', [$product]);
                        $json['success'] = Yii::t('app', 'AddtoCartMsg02');
                        $json['total'] = $total_product . " Sản phẩm - " . number_format($total_price) . " VND";
                    } else {
                        foreach ($product_cart as $key => $item) {
                            $product_price = (new Query())->select('price')->from('product')->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                            $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $item['product_quantity'];
                            $total_product += $item['product_quantity'];
                            if (($item['product_id'] == $product['product_id'])) {
                                $product_cart[$key]['product_quantity'] = $item['product_quantity'] + $product_quantity;
                                $flag = false;
                            }
                        }
                        if ($flag) {
                            array_push($product_cart, $product);
                            Yii::$app->session->set('product_cart', $product_cart);
                        } else {
                            Yii::$app->session->set('product_cart', $product_cart);
                        }
                        $json['success'] = Yii::t('app', 'AddtoCartMsg02');
                        $json['total'] = ($total_product) . " Sản phẩm - " . number_format($total_price) . " VND";
                    }


                }
            }
        }
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }
    }

    public function actionGetCartInfo()
    {
        return $this->renderPartial('getCartInfo');
    }

    public function actionViewCart()
    {
        $cart_info = Yii::$app->Header->getCartInfo();
        return $this->render('viewCart', ['cart_info' => $cart_info]);
    }

    public function actionUpdateCart()
    {
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            $update_cart = $post_data['update_cart'];
            $product_cart = Yii::$app->session->get('product_cart');
            foreach ($update_cart as $id => $quantity) {
                foreach ($product_cart as $key => $item) {
                    if ($id == $item['product_id']) {
                        $product_cart[$key]['product_quantity'] = $quantity;
                    }
                }
            }
            Yii::$app->session->set('product_cart', $product_cart);
            Yii::$app->session->set('product_cart', $product_cart);
            return $this->redirect('index.php?r=site/view-cart');
        }
    }

    public function actionRemoveFromCart()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            if (isset($post_data['product_id'])) {
                $product_id = $post_data['product_id'];
            } else {
                $product_id = 0;
            }
            if ($product_id == 0) {
                $json['error'] = Yii::t('app', 'RemoveCartMsg01');
            } else {
                $total_price = 0;
                $total_product = 0;
                $product_cart = Yii::$app->session->get('product_cart');
                foreach ($product_cart as $key => $item) {
                    if (($item['product_id'] == $product_id)) {
                        unset($product_cart[$key]);
                    } else {
                        $product_price = (new Query())->select('price')->from('product')->where(['id' => $item['product_id']])->one();
                        $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                        $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $item['product_quantity'];
                        $total_product += $item['product_quantity'];
                    }
                }
                Yii::$app->session->set('product_cart', $product_cart);
                $json['success'] = Yii::t('app', 'RemoveCartMsg02');
                $json['total'] = ($total_product) . " Sản phẩm - " . number_format($total_price) . " VND";
            }
        }
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }
    }

    public function actionVoucher()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            $voucher = $post_data['voucher'];
            $check_voucher = Voucher::find()->where(['code' => $voucher])->one();
            $today = date("d-m");
            $voucher_start_date = date("d-m", $check_voucher['start_date']);
            $voucher_end_date = date("d-m", $check_voucher['end_date']);
            if ($check_voucher['active'] == 0) {
                $json['error'] = Yii::t('app', 'InputVoucherMsg01');
            } else if ($today < $voucher_start_date) {
                $json['error'] = Yii::t('app', 'InputVoucherMsg02') . $voucher_start_date;
            } else if ($today > $voucher_end_date) {
                $json['error'] = Yii::t('app', 'InputVoucherMsg03') . $voucher_end_date;
            } else {
                $discount = $check_voucher['discount'];
            }

        }
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }
    }

    public function actionCheckout()
    {
        $personal_info = null;
        $address = null;
        $district = null;
        $city = null;
        if(!Yii::$app->user->isGuest){
            $customer = (new Query())->select(['guest_id','address_id'])->from('customer')->where(['id'=>Yii::$app->user->identity->getId()])->one();
            if(!empty($customer['guest_id'])){
                $personal_info = (new Query())->select(['full_name','email','phone_number'])->from('guest')->where(['id'=>$customer['guest_id']])->one();
                $address_id = $customer['address_id'];
                if(!empty($address_id)){
                    $address = (new Query())->select(['detail','district_id'])->from('address')->where(['id'=>$address_id])->one();
                    $district = (new Query())->select(['name','city_id'])->from('district')->where(['id'=>$address['district_id']])->one();
                    $city = (new Query())->select(['name'])->from('city')->where(['id'=>$district['city_id']])->one();
                }
            }
        }
        $modelCity = new City();
        $modelDistrict = new District();
        $city = City::find()->all();
        $district = District::find()->all();
        $listCity = ArrayHelper::map($city,'id','name');
        $listDistrict = ArrayHelper::map($district,'id','name');
        return $this->render('checkout',[
            'personal_info'=>$personal_info,
            'address'=>$address['detail'],
            'listCity'=>$listCity,
            'listDistrict'=>$listDistrict,
            'modelDistrict'=>$modelDistrict,
            'modelCity'=>$modelCity,
        ]);
    }

    public function actionRating()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            if (Yii::$app->user->isGuest)
                $json['error'] = Yii::t('app', 'RatingProductMsg01');
            else {
                $postData = Yii::$app->request->post();
                if (isset($postData['product_id'])) {
                    $product_id = $postData['product_id'];
                } else {
                    $product_id = 0;
                }
                $check_exist_rating = ProductRating::find()->where(['product_id' => $product_id, 'customer_id' => Yii::$app->user->identity->getId()])->one();
                if (!empty($check_exist_rating['rating_id'])) {

                    $json['error'] = Yii::t('app', 'RatingProductMsg02');
                } else {
                    if (Product::find()->where(['id' => $product_id, 'active' => 1])->exists()) {
                        if (isset($postData['score'])) {
                            $score = $postData['score'];
                        } else
                            $score = 5;
                        $rating_info = Rating::find()->where(['rating' => $score])->one();
                        try {
                            $product_rating = new ProductRating();
                            $product_rating->product_id = $product_id;
                            $product_rating->rating_id = $rating_info['id'];
                            $product_rating->customer_id = Yii::$app->user->identity->getId();
                            $product_rating->save();
                            $json['success'] = Yii::t('app', 'RatingProductMsg03') . $score . Yii::t('app', 'RatingProductMsg04');
                        }catch (\yii\db\Exception $connection){
                            $json['error'] = "Lỗi kết nối! bạn vui lòng thử lại sau ít phút";
                        }
                    } else {
                        $json['error'] = Yii::t('app', 'RatingProductMsg05');
                    }
                }
            }
        } else {
            $json['error'] = Yii::t('app', 'RatingProductMsg05');
        }


        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('successful', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'RequestPasswordResetMsg01'),
                    'title' => Yii::t('app', 'ForgottenPasswordLabel'),
                ]);

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('failed', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'RequestPasswordResetMsg02'),
                    'title' => Yii::t('app', 'ForgottenPasswordLabel'),
                ]);
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('successful', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 3000,
                'icon' => 'fa fa-plus',
                'message' => Yii::t('app', 'ResetPasswordMsg01'),
                'title' => Yii::t('app', 'ChangePassInfoLabel'),
            ]);

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {

        $modelCustomer = new Customer();
        $modelCustomer->scenario = 'addCustomer';
        $modelGuest = new Guest();

        if ($modelCustomer->load(Yii::$app->request->post())
            && $modelGuest->load(Yii::$app->request->post())
        ) {

            //Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //save guest to database
                if ($modelGuest->save()) {
                    $modelCustomer->guest_id = $modelGuest->id;

                    if ($user = $modelCustomer->register()) {
                        $transaction->commit();
                        if (Yii::$app->getUser()->login($user)) {
                            $this->goHome();
                        }
                    } else {
                        return $this->render('register', [
                            'modelCustomer' => $modelCustomer,
                            'modelGuest' => $modelGuest,
                        ]);
                    }
                } else {
                    return $this->render('register', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            return $this->render('register', [
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
            ]);
        }

    }
}
