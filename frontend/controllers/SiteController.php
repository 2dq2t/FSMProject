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
use common\models\WishList;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\data\Pagination;
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
        $tag_id = (new Query())->select('id')->from('tag')->where(['name'=>'new'])->one();
        if(!empty($tag_id['id'])) {
            $new_product_from_tag =(new Query())->select(['product.id as product_id','product.name as product_name','product.price as product_price','product.tax as product_tax'])->from('product')->innerJoin('product_tag', 'product.id = product_tag.product_id')->where(['product.active' => 1, 'product_tag.tag_id' => $tag_id['id']])->all();

        }
        $new_product_from_system = (new Query())->select(['id as product_id','name as product_name','price as product_price','tax as product_tax'])->from('product')->where(['active' => '1'])->orderBy(['id' => SORT_DESC])->limit(10)->all();
        //get product's image, product offer, product rating and loại bỏ giá trị trùng nhau with new product
        if(!empty($new_product_from_tag[0]['product_id'])) {
            foreach ($new_product_from_system as $key=> $item) {
                //get product image
                $product_image = Yii::$app->CommonFunction->getOneImage($item['product_id']);
                $new_product_from_system[$key]['product_image'] = $product_image;
                //get product offer
                $product_offer = Yii::$app->CommonFunction->getOffer($item['product_id']);
                $new_product_from_system[$key]['product_offer'] = $product_offer;
                //Get rating average
                $rating_average = Yii::$app->CommonFunction->rating($item['product_id']);
                $new_product_from_system[$key]['product_rating'] = $rating_average;

                foreach ($new_product_from_tag as $new_product_key => $new_product_item) {
                    //loại bỏ trùng product
                    if ($item['product_id'] == $new_product_item['product_id']) {
                        unset($new_product_from_tag[$new_product_key]);
                    } else {
                        //get product image
                        $product_image = Yii::$app->CommonFunction->getOneImage($new_product_item['product_id']);
                        $new_product_from_tag[$new_product_key]['product_image'] = $product_image;
                        //get product offer
                        $product_offer = Yii::$app->CommonFunction->getOffer($new_product_item['product_id']);
                        $new_product_from_tag[$new_product_key]['product_offer'] = $product_offer;

                        //Get rating average
                        $rating_average = Yii::$app->CommonFunction->rating($new_product_item['product_id']);
                        $new_product_from_tag[$new_product_key]['product_rating'] = $rating_average;
                    }
                }
            }
        }
        else{
            foreach ($new_product_from_system as $key=> $item) {
                //get product image
                $product_image = Yii::$app->CommonFunction->getOneImage($item['product_id']);
                $new_product_from_system[$key]['product_image'] = $product_image;
                //get product offer
                $product_offer = Yii::$app->CommonFunction->getOffer($item['product_id']);
                $new_product_from_system[$key]['product_offer'] = $product_offer;
                //Get rating average
                $rating_average = Yii::$app->CommonFunction->rating($item['product_id']);
                $new_product_from_system[$key]['product_rating'] = $rating_average;
            }
        }
        $new_product = array_merge($new_product_from_tag,$new_product_from_system);
        $new_product = Yii::$app->CommonFunction->custom_shuffle($new_product);

        //get product from season
        //check now season is?
        $product_season = array();
        $season = Season::find()->all();
        $season_id = null;
        foreach ($season as $season_item) {
            $season_from = date("d-m", $season_item['from']);
            $season_to = date("d-m",$season_item['to']);
            $today = date("d-m");
            if($season_from <= $today && $today <= $season_to){
                $season_id = $season_item['id'];
            }
        }
        $product_id = ProductSeason::find()->where(['season_id'=>$season_id])->all();
        if(!empty($product_id[0]['season_id'])){
            foreach($product_id as $product_item){
                $product = (new Query())->select(['id as product_id','name as product_name','price as product_price','tax as product_tax'])->from('product')->where(['active' => '1','id'=>$product_item['product_id']])->one();
                if(!empty($product['product_id'])) {
                    //get product image
                    $product_image = Yii::$app->CommonFunction->getOneImage($product_item['product_id']);
                    $product['product_image'] = $product_image;
                    //get product offer
                    $product_offer = Yii::$app->CommonFunction->getOffer($product_item['product_id']);
                    $product['product_offer'] = $product_offer;

                    //Get rating average
                    $rating_average = Yii::$app->CommonFunction->rating($product_item['product_id']);
                    $product['product_rating'] = $rating_average;

                    array_push($product_season,$product);
                    $product = null;
                }
            }
        }
        $product_season = Yii::$app->CommonFunction->custom_shuffle($product_season);

        //get slide image
        $slide_show = (new Query())->select(['slide_show.id as slide_show_id','slide_show.path as slide_show_path','product.name as product_name'])->from('slide_show')->leftJoin('product','slide_show.product_id = product.id')->where(['slide_show.active'=>1])->all();

        //get number wishlist

        return $this->render('index', [
             'slide_show' => $slide_show,'new_product'=>$new_product,
             'product_season'=>$product_season,
        ]);
    }

    public function actionSearch(){

    }
    public function actionCategory()
    {
        if (Yii::$app->request->isGet) {

            if (empty($_GET['category']))
                return $this->goHome();/*
            $sort = $_GET['sort'];;*/
            $category_name = $_GET['category'];
            $category_ID =(new Query())->select('id')->from('category')->where(['name' => $category_name])->one();
            $category_product = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                , 'product.tax as product_tax', 'image.path as image_path'])->from('product')->innerJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $category_ID['id']])->groupBy('product.id')->all();
            $page = new Pagination();


            return $this->render('category', [
                'category_name' => $category_name, 'category_product' => $category_product,
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
            $rating_average = Yii::$app->CommonFunction->rating($product_detail['id']);

            //get product offer
            $product_offer = Yii::$app->CommonFunction->getOffer($product_detail['id']);

            //get product image
            $product_image_detail = Image::find()->where(['product_id' => $product_detail['id']])->all();

            //get product unit
            $product_unit = (new Query())->select('name')->from('unit')->where(['id'=>$product_detail['unit_id']])->one();

            //get product tag
            $product_tag = (new Query())->select('name')->from('tag')->innerJoin('product_tag','tag.id = product_tag.tag_id')->where(['product_tag.product_id'=>$product_detail['id']])->all();

            /*//get product recent view
            $product_recent_view = array();
            $product['product_id'] = $product_detail['id'];
            $product['product_name'] = $product_detail['name'];
            $product['product_price'] = $product_detail['price'];
            $product['product_rating'] = $rating_average;
            $product['product_offer'] = $product_offer;
            $product['product_image'] = $product_image[0]['path'];
            $product_session = Yii::$app->session->get('product_session');
            if(count($product_session) == 0 ) {
                Yii::$app->session->set('product_session', $product);
            }
            else {
                array_push($product_recent_view, $product_session);
                array_push($product_recent_view,$product);
                Yii::$app->session->set('product_session',$product_recent_view);
            }
            echo "<pre>";
            print_r($product_recent_view) ;
            echo "</pre>";*/

            return $this->render('viewDetail', [
                'product_detail' => $product_detail,'product_image_detail' => $product_image_detail,
                'product_offer' => $product_offer,'rating_average' => $rating_average,
                'product_unit'=>$product_unit,'product_tag'=>$product_tag,
            ]);
        }
    }
    public function actionWishList(){
        if (Yii::$app->request->post()) {

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
                    $json['total'] = WishList::find()->count('customer_id');
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
       /* if (isset(Yii::$app->request->post())) {
            $product_id = Yii::$app->request->post['product_id'];
        } else {
            $product_id = 0;
        }
        if (Product::find()->where(['id' => $product_id, 'active' => 1])->exists()) {
            $product_info = Product::find()->where(['id' => $product_id, 'active' => 1])->all();
            if (isset(Yii::$app->request->post['quantity'])) {
                $quantity = Yii::$app->request->post['quantity'];
            } else {
                $quantity = 1;
            }

        }*/
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [json_encode($json)];
        }
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
                $check_exist_rating= ProductRating::find()->where(['product_id'=>$product_id,'customer_id'=>Yii::$app->user->identity->getId()])->one();
                if(!empty($check_exist_rating['rating_id']))
                    $json['error'] = "Sản phẩm này đã được bạn đánh giá!";
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
