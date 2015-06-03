<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Customer;
use common\models\Image;
use common\models\Offer;
use common\models\Product;
use common\models\ProductRating;
use common\models\ProductSeason;
use common\models\Rating;
use common\models\Season;
use common\models\SlideShow;
use common\models\Tag;
use common\models\Ward;
use common\models\Address;
use common\models\WishList;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\helpers\StringHelper;
use yii\validators\StringValidator;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\JSon;
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
        $tag_id = Tag::find()->where(['name'=>'new'])->one();
        if(!empty($tag_id['id'])) {
            $product_query = new Query();
            $product_query->select(['product.id as product_id','product.name as product_name','product.price as product_price','product.tax as product_tax'])->from('product')->leftJoin('product_tag', 'product.id = product_tag.product_id')->where(['product.active' => 1, 'product_tag.tag_id' => $tag_id['id']]);
            $product_command = $product_query->createCommand();
            $new_product_from_tag = $product_command->queryAll();
        }
        $new_product_from_system = (new \yii\db\Query())->select(['id as product_id','name as product_name','price as product_price','tax as product_tax'])->from('product')->where(['active' => '1'])->orderBy(['id' => SORT_DESC])->limit(10)->all();
        //get product's image, product offer, product rating and loại bỏ giá trị trùng nhau with new product
        if(!empty($new_product_from_tag[0]['product_id'])) {
            foreach ($new_product_from_system as $key=> $item) {
                //get product image
                $product_image = Image::find()->where(['product_id' => $item['product_id']])->one();
                $new_product_from_system[$key]['product_image'] = $product_image['path'];
                //get product offer
                $offer = (new \yii\db\Query())->select('price,start_date,end_date')->from('offer')->where(['active'=>1,'product_id'=>[$item['product_id']]])->one();
                $today = date("d-m-Y");
                $offer_start_date = date("d-m-Y",$offer['start_date']);
                $offer_end_date = date("d-m-Y",$offer['end_date']);
                if($offer_start_date <= $today && $today <= $offer_end_date) {
                    $product_offer = $offer['price'];
                    $item['product_offer'] = $product_offer;
                    $new_product_from_system[$key]['product_offer']=$product_offer;
                }
                else
                    $new_product_from_system[$key]['product_offer']=null;
                //Get rating average
                $rating_id = ProductRating::find('rating_id')->where(['product_id' => $item['product_id']])->all();
                $total_score = 0;
                $count_rating = 0;
                foreach ($rating_id as $rating) {
                    $count_rating++;
                    $score = Rating::find('rating')->where(['id' => $rating['rating_id']])->one();
                    $total_score += $score['rating'];
                }
                if($total_score > 0 && $count_rating >0) {
                    $rating_average = $total_score / $count_rating;
                    $new_product_from_system[$key]['product_rating']=$rating_average;
                }
                else
                    $new_product_from_system[$key]['product_rating'] = 0;

                foreach ($new_product_from_tag as $new_product_key => $new_product_item) {
                    //loại bỏ trùng product
                    if ($item['product_id'] == $new_product_item['product_id']) {
                        unset($new_product_from_tag[$new_product_key]);
                    } else {
                        //get product image
                        $product_image = Image::find()->where(['product_id' => $item['product_id']])->one();
                        $new_product_from_system[$key]['product_image'] = $product_image['path'];
                        //get product offer
                        $offer = (new \yii\db\Query())->select('price,start_date,end_date')->from('offer')->where(['active'=>1,'product_id'=>[$item['product_id']]])->one();
                        $offer_start_date = date("d-m-Y",$offer['start_date']);
                        $offer_end_date = date("d-m-Y",$offer['end_date']);
                        if($offer_start_date <= $today && $today <= $offer_end_date) {
                            $product_offer = $offer['price'];
                            $new_product_from_system[$key]['product_offer']= $product_offer;
                        }
                        else
                            $new_product_from_system[$key]['product_offer']= null;

                        //Get rating average
                        $rating_id = ProductRating::find('rating_id')->where(['product_id' => $item['product_id']])->all();
                        $total_score = 0;
                        $count_rating = 0;
                        foreach ($rating_id as $rating) {
                            $count_rating++;
                            $score = Rating::find('rating')->where(['id' => $rating['rating_id']])->one();
                            $total_score += $score['rating'];
                        }
                        if($total_score > 0 && $count_rating >0) {
                            $rating_average = $total_score / $count_rating;
                            $new_product_from_system[$key]['product_rating']=$rating_average;
                        }
                        else
                            $new_product_from_system[$key]['product_rating'] = 0;
                    }
                }
            }
        }
        else{
            foreach ($new_product_from_system as $key=> $item) {
                //get product image
                $product_image = Image::find()->where(['product_id' => $item['product_id']])->one();
                $new_product_from_system[$key]['product_image'] = $product_image['path'];
            }
        }
        $new_product = array_merge($new_product_from_tag,$new_product_from_system);

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
        if(count($product_id)>0){
            foreach($product_id as $product_item){
                $product = (new \yii\db\Query())->select(['id as product_id','name as product_name','price as product_price','tax as product_tax'])->from('product')->where(['active' => '1','id'=>$product_item['product_id']])->one();
                if(!empty($product['product_id'])) {
                    //get product image
                    $product_image = Image::find()->where(['product_id' => $product_item['product_id']])->one();
                    $product['product_image'] = $product_image['path'];
                    //get product offer
                    $offer = (new \yii\db\Query())->select('price,start_date,end_date')->from('offer')->where(['active' => 1, 'product_id' => $product_item['product_id']])->one();
                    $offer_start_date = date("d-m-Y", $offer['start_date']);
                    $offer_end_date = date("d-m-Y", $offer['end_date']);
                    $today = date("d-m-Y");
                    if ($offer_start_date <= $today && $today <= $offer_end_date) {
                        $product_offer = $offer['price'];
                        $product['product_offer'] = $product_offer;
                    } else
                        $product['product_offer'] = null;

                    //Get rating average
                    $rating_id = ProductRating::find('rating_id')->where(['product_id' => $product_item['product_id']])->all();
                    $total_score = 0;
                    $count_rating = 0;
                    foreach ($rating_id as $rating) {
                        $count_rating++;
                        $score = Rating::find('rating')->where(['id' => $rating['rating_id']])->one();
                        $total_score += $score['rating'];
                    }
                    if ($total_score > 0 && $count_rating > 0) {
                        $rating_average = $total_score / $count_rating;
                        $product['product_rating'] = $rating_average;
                    } else
                        $product['product_rating'] = null;
                    array_push($product_season,$product);
                    $product = null;
                }
            }
        }

        //get special product - product has offer

        //get bestseller product

        //get category in navbar
        $query = new Query();
        $query->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
            ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => 1]);
        $command = $query->createCommand();
        $categories = $command->queryAll();

        //get slide image
        $slide_query = new Query();
        $slide_query->select(['slide_show.id as slide_show_id','slide_show.path as slide_show_path','product.name as product_name'])->from('slide_show')->leftJoin('product','slide_show.product_id = product.id')->where(['slide_show.active'=>1]);
        $slide_command = $slide_query->createCommand();
        $slide_show = $slide_command->queryAll();

        /*echo '<pre>';
        print_r($product_season);
        echo '</pre>';*/
        return $this->render('index', [
            'categories' => $categories, 'slide_show' => $slide_show,
            'new_product'=>$new_product, 'product_season'=>$product_season,
        ]);
    }

    public function actionCategory()
    {
        if (Yii::$app->request->isGet) {

            if (empty($_GET['category']))
                return $this->goHome();/*
            $sort = $_GET['sort'];
            $limit = $_GET['limit'];*/
            $categoryName = $_GET['category'];
            $categoryID = Category::find()->where(['name' => $categoryName])->one();
            $productQuery = new Query();
            $productQuery->select(['product.id as product_id', 'product.name as product_name', 'product.intro as product_intro', 'product.price as product_price'
                , 'product.tax as product_tax', 'image.path as image_path'])->from('product')->leftJoin('image', 'product.id = image.product_id')->where(['product.active' => 1, 'product.category_id' => $categoryID['id']])->groupBy('product.id');
            $productCommand = $productQuery->createCommand();
            $page = new Pagination();
            $product = $productCommand->queryAll();

            $query = new Query();
            $query->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
                ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => 1]);
            $command = $query->createCommand();
            $categories = $command->queryAll();
            return $this->render('category', [
                'categories' => $categories, 'category_name' => $categoryName, 'product' => $product
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
            $rating_id = ProductRating::find('rating_id')->where(['product_id' => $product_detail['id']])->all();
            $total_score = 0;
            $count_rating = 0;
            foreach ($rating_id as $item) {
                $count_rating++;
                $score = Rating::find('rating')->where(['id' => $item['rating_id']])->one();
                $total_score += $score['rating'];
            }
            if($total_score > 0 && $count_rating >0) {
                $rating_average = $total_score / $count_rating;
            }
            else
                $rating_average = 0;
            //select category in navbar
            $query = new Query();
            $query->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
                ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => 1]);
            $command = $query->createCommand();
            $categories = $command->queryAll();

            //get product image
            $product_image = Image::find()->where(['product_id' => $product_detail['id']])->all();

            return $this->render('viewDetail', [
                'product_detail' => $product_detail,
                'product_image' => $product_image,
                'categories' => $categories,
                'rating_average' => $rating_average
            ]);
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
        if (isset(Yii::$app->request->post['product_id'])) {
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

        }
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
                $json['info'] = "Bạn phải đăng nhập để thực hiện chức năng này";
            else {
                $postData = Yii::$app->request->post();
                if (isset($postData['product_id'])) {
                    $product_id = $postData['product_id'];
                } else {
                    $product_id = 0;
                }
                if (Product::find()->where(['id' => $product_id, 'active' => 1])->exists()) {
                    if (isset($postData['score'])) {
                        $score = $postData['score'];
                    } else
                        $score = 5;
                    $rating_info = Rating::find()->where(['rating' => $score])->one();
                    $product_rating = new ProductRating();
                    $product_rating->product_id = $product_id;
                    $product_rating->rating_id = $rating_info['id'];
                    //$product_rating->save();
                    $json['success'] = "Bạn đã đánh giá " . $score . " sao cho sản phẩm này";
                } else {
                    $json['error'] = "Có lỗi xảy ra, liên hệ với chúng tôi để biết thêm chi tiết!";
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
            //select category in navbar
            $query = new Query();
            $query->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
                ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => 1]);
            $command = $query->createCommand();
            $categories = $command->queryAll();
            return $this->render('login', [
                'model' => $model, 'categories' => $categories
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
        $query = new Query();
        $query->select(['category.name as categoryname', 'product.name as productname', 'product.id as productId'])
            ->from('category')->leftJoin('product', 'category.id = product.category_id')->where(['category.active' => 1]);
        $command = $query->createCommand();
        $categories = $command->queryAll();
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
                            'categories' => $categories,
                        ]);
                    }
                } else {
                    return $this->render('register', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                        'categories' => $categories,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            return $this->render('register', [
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
                'categories' => $categories,
            ]);
        }

    }
}
