<?php
namespace frontend\controllers;

use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Customer;
use common\models\Image;
use common\models\Product;
use common\models\Rating;
use common\models\Season;
use common\models\SlideShow;
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
use yii\data\SqlDataProvider;
use yii\db\Query;
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
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;
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
        //select category in navbar
        $query = new Query();
        $query->select(['category.name as categoryname', 'product.name as productname'])
               ->from('category')->leftJoin('product', 'category.id = product.category_id');
        $command = $query->createCommand();
        $modelCategory = $command->queryAll();
        /*$provider = new SqlDataProvider([
            'sql' => 'SELECT product.id,product.name,product.price,image.path FROM product,image WHERE product.active=:active AND product.id=image.product_id ORDER BY product.id DESC ',
            'params' => [':active' => 1],
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        $product = $provider->getModels();*/
        $newProduct = Product::find()->where(['active'=>'1'])->orderBy(['id'=>SORT_DESC])->limit(5)->all();
        foreach($newProduct as $item){
            $imagePath = Image::find()->where(['product_id'=>$item['id']])->one();
            print_r($imagePath);
        }
        $season = Season::find(['from','to'])->all();
        $slideShow = SlideShow::find()->all();
        echo '<pre>';
        //print_r($newProduct) ;
        //print_r($season);
        echo '</pre>';
        return $this->render('index',[
            'modelCategory' => $modelCategory,'slideShow'=>$slideShow,
        ]);
    }

    public function actionViewDetail()
    {
        if (Yii::$app->request->isGet) {

            if (empty($_GET['product']))
                return $this->goHome();
            $productName = $_GET['product'];
            $productDetail = Product::find()->where(['name' => $productName])->all();
            $productImage = Image::find()->where(['product_id' => $productDetail['0']['id']])->all();
            $starRating = new Rating();
            return $this->render('viewDetail', ['productDetail' => $productDetail, 'productImage' => $productImage, 'starRating' => $starRating]);
        }
    }

    public function actionWishList()
    {
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $productId = json_decode($data['id']);
            $wishList = new WishList();
            $wishList->product_id = $productId;
            $wishList->customer_id = \Yii::$app->user->identity->getId();
            $wishList->save();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['productId'=>$productId];
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

    public function actionRegister(){

        $modelCustomer = new Customer();
        $modelGuest = new Guest();

        if($modelCustomer->load(Yii::$app->request->post())
            && $modelGuest->load(Yii::$app->request->post())){

            //Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //save guest to database
                if ($modelGuest->save()) {
                    $modelCustomer->guest_id = $modelGuest->id;

                    if($user = $modelCustomer->register()){
                        $transaction->commit();
                        if(Yii::$app->getUser()->login($user)){
                            $this->goHome();
                        }
                    }
                    else{
                        return $this->render('register', [
                            'modelCustomer' => $modelCustomer,
                            'modelGuest' => $modelGuest,
                        ]);
                    }
                }else{
                    return $this->render('register', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                    ]);
                }
            }catch (Exception $e){
                $transaction->rollBack();
            }
        }else{
            return $this->render('register',[
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
            ]);
        }

    }
}
