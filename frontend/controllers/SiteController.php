<?php
namespace frontend\controllers;

use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Customer;
use common\models\Product;
use common\models\Ward;
use common\models\Address;
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

/**
 * Site controller
 */
class SiteController extends Controller
{
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
        //select category in navbar
        $query = new Query();
        $query->select(['category.name as categoryname', 'product.name as productname'])
               ->from('category')->leftJoin('product', 'category.id = product.category_id');
        $command = $query->createCommand();
        $modelCategory = $command->queryAll();
        $provider = new SqlDataProvider([
            'sql' => 'SELECT product.id,product.name,product.price,image.path FROM product,image WHERE product.active=:active AND product.id=image.product_id ORDER BY product.id DESC ',
            'params' => [':active' => 1],
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        $product = $provider->getModels();
        $slideShow = \common\models\SlideShow::find()->all();
        /*echo '<pre>';
        print_r($product) ;
        echo '</pre>';*/
        return $this->render('index',[
            'modelCategory' => $modelCategory,'product'=> $product,'slideShow'=>$slideShow
        ]);
    }

    public function actionViewDetail()
    {
        if(Yii::$app->request->isGet){

            if(empty($_GET['product']))
                return $this->goHome();
            $productName  = $_GET['product'];
            $productDetail = \common\models\Product::find()->where(['name'=>$productName])->all();
            $productImage = \common\models\Image::find()->where(['product_id'=>$productDetail['0']['id']])->all();
            return $this->render('viewDetail',['productDetail'=>$productDetail,'productImage'=>$productImage]);
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
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
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
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionRegister(){

        $modelCustomer = new Customer();
        $modelGuest = new Guest();
        $modelCity = new City();
        $modelDistrict = new District();
        $modelWard = new Ward();
        $modelAddress = new Address();

        if($modelCustomer->load(Yii::$app->request->post())
            && $modelGuest->load(Yii::$app->request->post())
            && $modelAddress->load(Yii::$app->request->post())){

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($modelAddress->save()) {
                    $modelCustomer->address_id = $modelAddress->id;

                    if ($user = $modelCustomer->register()) {
                        $modelGuest->customer_id = $user->id;
                        $modelGuest->save();

                        $transaction->commit();

                        if (Yii::$app->getUser()->login($user)) {
                            return $this->goHome();
                        }
                    }else{
                        return $this->render('register', [
                            'modelCustomer' => $modelCustomer,
                            'modelGuest' => $modelGuest,
                            'modelCity' => $modelCity,
                            'modelDistrict' => $modelDistrict,
                            'modelWard' => $modelWard,
                            'modelAddress' => $modelAddress,
                        ]);
                    }
                }else{
                    return $this->render('register', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                        'modelCity' => $modelCity,
                        'modelDistrict' => $modelDistrict,
                        'modelWard' => $modelWard,
                        'modelAddress' => $modelAddress,
                    ]);
                }
            }catch (Exception $e){
                $transaction->rollBack();
            }
        }else{
            return $this->render('register',[
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
                'modelCity' => $modelCity,
                'modelDistrict' => $modelDistrict,
                'modelWard' => $modelWard,
                'modelAddress' => $modelAddress,
            ]);
        }

    }

    public function actionSubcat() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $city_id = $parents[0];
                $out = District::getOptionsByDistrict($city_id);
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionProd() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $cat_id = empty($ids[0]) ? null : $ids[0];
            $subcat_id = empty($ids[1]) ? null : $ids[1];
            if ($cat_id != null && $subcat_id != null) {
                $data = Ward::getOptionsByWard($subcat_id);
                echo Json::encode(['output'=>$data, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
}
