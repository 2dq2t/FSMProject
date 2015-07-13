<?php
namespace frontend\controllers;

use common\models\Category;
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
use frontend\models\ContactForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\db\Expression;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\alert\Alert;
use yii\web\Session;

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
                $rating_average = Yii::$app->CommonFunction->productRating($item['product_id']);
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
                        $rating_average = Yii::$app->CommonFunction->productRating($new_product_item['product_id']);
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
                $rating_average = Yii::$app->CommonFunction->productRating($item['product_id']);
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
            $season_from = date("m-d", $season_item['from']);
            $season_to = date("m-d", $season_item['to']);
            $today = date("m-d");
            if ($season_from <= $today && $today <= $season_to) {
                $product_id = ProductSeason::find()->where(['season_id' =>$season_item['id']])->all();
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
                            $rating_average = Yii::$app->CommonFunction->productRating($product_item['product_id']);
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

    public function actionPrefetch() {
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
        foreach($products as $product) {
            $out[] = $product;
        }

        echo json_encode($out);
    }

    public function actionSearch($q){
        $query = new Query();
        $query->select('product.name')
            ->from('product')
            ->join('INNER JOIN', 'category', 'product.category_id = category.id')
            ->where('category.active = ' . Category::STATUS_ACTIVE . ' AND product.active = ' . Product::STATUS_ACTIVE . ' AND (' .
                'MATCH(product.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                'MATCH(category.name) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE) OR ' .
                'MATCH(product.description) AGAINST("' . mysql_real_escape_string($q) . '*" IN BOOLEAN MODE)
                )');
        $command = $query->createCommand();
        $products = $command->queryAll();
        return $this->render('search', ['products' => $products]);
    }

    public function actionCategory()
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
                        , 'product.tax as product_tax', 'image.path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $category_ID['id']])->groupBy('product.id')->orderBy(['product.' . $sort => $order]);
                    $countQuery = clone $category_product_query;
                    $pages = new Pagination(['totalCount' => $countQuery->count()]);
                    $category_product = $category_product_query->offset($pages->offset)->limit($pages->limit)->all();
                } else {
                    $category_product_query = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                        , 'product.tax as product_tax', 'image.path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $category_ID['id']])->groupBy('product.id');
                    $countQuery = clone $category_product_query;
                    $pages = new Pagination(['totalCount' => $countQuery->count()]);
                    $category_product = $category_product_query->offset($pages->offset)->limit($pages->limit)->all();
                }

            }


            return $this->render('category', [
                'category_name' => $category_name, 'category_product' => $category_product, 'pages' => $pages
            ]);
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
            $rating_average = Yii::$app->CommonFunction->productRating($product_detail['id']);

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
                    $product_image = Yii::$app->CommonFunction->getProductOneImage($item['product_id']);
                    $item['product_image'] = $product_image;
                    //get product offer
                    $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                    $item['product_offer'] = $product_offer;

                    //Get rating average
                    $rating_average = Yii::$app->CommonFunction->productRating($item['product_id']);
                    $item['product_rating'] = $rating_average;

                    array_push($products_same_category, $item);
                }
            }
            //set product recent view
            $flag = true;
            $product['product_id'] = $product_detail['id'];
            $product['product_name'] = $product_detail['name'];
            $product['product_price'] = $product_detail['price'];
            $product['product_rating'] = $rating_average;
            $product['product_offer'] = $product_offer;
            $product['product_image'] = $product_image_detail[0]['path'];
            $product['product_tax'] = $product_detail['tax'];
            $product_session = Yii::$app->session->get('product_session');
            if (count($product_session) == 0) {
                Yii::$app->session->set('product_session', [$product]);
            } else {
                foreach ($product_session as $item) {
                    if (($item['product_id'] == $product['product_id'])) {
                        $flag = false;
                    }
                }
                if ($flag) {
                    array_push($product_session, $product);
                    Yii::$app->session->set('product_session', $product_session);
                }
            }
            //reg JS facebook comment



            return $this->render('viewDetail', [
                'product_detail' => $product_detail, 'product_image_detail' => $product_image_detail,
                'product_offer' => $product_offer, 'rating_average' => $rating_average,
                'product_unit' => $product_unit, 'product_tag' => $product_tag,
                'products_same_category' => $products_same_category,
            ]);
        }
    }

    public function actionWishList()
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
            $product_session = Yii::$app->session->get('product_session');
            return $this->render('wishList', [
                'wish_list_product' => $wish_list_product,
                'product_session' => $product_session,
            ]);
        }
    }

    public function actionRemoveWishList()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            if (Yii::$app->user->isGuest) {
                return $this->goHome();
            } else {
                try {
                    $post_data = Yii::$app->request->post();
                    $product_id = json_decode($post_data['product_id']);
                    $customer_id = Yii::$app->user->identity->getId();
                    WishList::findOne(['customer_id' => $customer_id, 'product_id' => $product_id])->delete();
                    $json['success'] = "Đã xóa thành công sản phẩm khỏi danh mục yêu thích";
                    $json['product_id'] = $product_id;
                    $json['total'] = WishList::find()->where(['customer_id' => $customer_id])->count();
                } catch (\mysqli_sql_exception $ex) {
                    $json['error'] = "Đã có lỗi xảy ra, vui lòng liên hệ để biết thêm chi tiết";
                }
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
                $json['info'] = "Bạn phải đăng nhập để thực hiện chức năng này";
            else {
                $customer_id = Yii::$app->user->identity->getId();
                $data = Yii::$app->request->post();
                $product_id = json_decode($data['product_id']);
                if (WishList::find()->where(['customer_id' => $customer_id, 'product_id' => $product_id])->exists()) {
                    $json['info'] = "Sản phẩm đã tồn tại trong danh mục yêu thích!";
                } else {
                    //save to wish list
                    $wishList = new WishList();
                    $wishList->customer_id = $customer_id;
                    $wishList->product_id = $product_id;
                    $wishList->save();
                    $json['total'] = WishList::find()->where(['customer_id' => $customer_id])->count();
                    $json['success'] = "Đã thêm vào danh mục yêu thích";
                }
            }
        } else
            $json['info'] = "Đã có lỗi xảy ra, liên hệ với chúng tôi để biết thêm chi tiết";
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
                $json['error'] = "Có lỗi xảy ra, liên hệ chúng tôi để biết thêm chi tiết";
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
                    $total_price += Yii::$app->CommonFunction->productPrice($product_price['price'], $product_offer) * $product_quantity;
                    $total_product += $product_quantity;
                    if (count($product_cart) == 0) {
                        Yii::$app->session->set('product_cart', [$product]);
                        $json['success'] = "Đã thêm thành công sản phẩm vào giỏ hàng";
                        $json['total'] = $total_product . " Sản phẩm - " . number_format($total_price) . " VND";
                    } else {
                        foreach ($product_cart as $key => $item) {
                            $product_price = (new Query())->select('price')->from('product')->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                            $total_price += Yii::$app->CommonFunction->productPrice($product_price['price'], $product_offer) * $item['product_quantity'];
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
                        $json['success'] = "Đã thêm thành công sản phẩm vào giỏ hàng";
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

    public function actionCartInfo()
    {
        return $this->renderPartial('cartInfo');
    }

    public function actionViewCart()
    {
        $cart_info = Yii::$app->Header->cartInfo();
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
                $json['error'] = "Có lỗi xảy ra, liên hệ chúng tôi để biết thêm chi tiết";
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
                        $total_price += Yii::$app->CommonFunction->productPrice($product_price['price'], $product_offer) * $item['product_quantity'];
                        $total_product += $item['product_quantity'];
                    }
                }
                Yii::$app->session->set('product_cart', $product_cart);
                $json['success'] = "Đã xóa sản phẩm khỏi giỏ hàng";
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
                $json['error'] = "Mã giảm giá đã bị khóa, vui lòng liên hệ để biết thêm chi tiết";
            } else if ($today < $voucher_start_date) {
                $json['error'] = "Lỗi! Mã giảm giá có giá trị sử dụng từ ngày " . $voucher_start_date;
            } else if ($today > $voucher_end_date) {
                $json['error'] = "Mã giảm giá đã hết hạn từ ngày " . $voucher_end_date;
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
        return $this->render('checkout');
    }

    public function actionRate()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            if (Yii::$app->user->isGuest)
                $json['error'] = "Bạn phải đăng nhập để thực hiện chức năng này";
            else {
                $postData = Yii::$app->request->post();
                if (isset($postData['product_id'])) {
                    $product_id = $postData['product_id'];
                } else {
                    $product_id = 0;
                }
                $check_exist_rating = ProductRating::find()->where(['product_id' => $product_id, 'customer_id' => Yii::$app->user->identity->getId()])->one();
                if (!empty($check_exist_rating['rating_id']))
                    $json['error'] = "Bạn chỉ được đánh giá một lần/ 1 sản phẩm!";
                else {
                    if (Product::find()->where(['id' => $product_id, 'active' => 1])->exists()) {
                        if (isset($postData['score'])) {
                            $score = $postData['score'];
                        } else
                            $score = 5;
                        $rating_info = Rating::find()->where(['rating' => $score])->one();
                        $product_rating = new ProductRating();
                        $product_rating->product_id = $product_id;
                        $product_rating->rating_id = $rating_info['id'];
                        $product_rating->customer_id = Yii::$app->user->identity->getId();
                        $product_rating->save();
                        $json['success'] = "Bạn đã đánh giá " . $score . " sao cho sản phẩm này";
                    } else {
                        $json['error'] = "Có lỗi xảy ra, liên hệ với chúng tôi để biết thêm chi tiết!";
                    }
                }
            }
        } else {
            $json['error'] = "Có lỗi xảy ra, liên hệ với chúng tôi để biết thêm chi tiết!";
        }


        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }
    }

    public function actionLogin()
    {
        /*if (Yii::$app->request->post() && Yii::$app->request->isAjax) {
            $html = <<<HTML
               <!-- <div class="row">
                  <div class="col-sm-6">
                    <h2>New Customer</h2>
                    <p>Checkout Options:</p>
                    <div class="radio">
                      <label>
                                <input type="radio" name="account" value="register" checked="checked">
                                Register Account</label>
                    </div>
                        <div class="radio">
                      <label>
                                <input type="radio" name="account" value="guest">
                                Guest Checkout</label>
                    </div>
                        <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
                    <input type="button" value="Continue" id="button-account" data-loading-text="Loading..." class="btn btn-primary">
                  </div>
                  <div class="col-sm-6">
                    <h2>Returning Customer</h2>
                    <p>I am a returning customer</p>
                    <div class="form-group">
                      <label class="control-label" for="input-email">E-Mail</label>
                      <input type="text" name="email" value="" placeholder="E-Mail" id="input-email" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="input-password">Password</label>
                      <input type="password" name="password" value="" placeholder="Password" id="input-password" class="form-control">
                      <div class="forget-password"><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/forgotten">Forgotten Password</a></div></div>
                    <input type="button" value="Login" id="button-login" data-loading-text="Loading..." class="btn btn-primary">
                  </div>
                </div>-->
HTML;
            return $this->renderAjax($html);
        } else {*/
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
      //  }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
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
                    'message' => Yii::t('app', 'Check your email for further instructions.'),
                    'title' => Yii::t('app', 'Request password reset'),
                ]);

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('successful', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'Sorry, we are unable to reset password for email provided.'),
                    'title' => Yii::t('app', 'Request password reset'),
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
                'message' => Yii::t('app', 'New password was saved.'),
                'title' => Yii::t('app', 'Password reset'),
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
