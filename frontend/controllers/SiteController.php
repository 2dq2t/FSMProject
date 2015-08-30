<?php
namespace frontend\controllers;

use common\models\Faq;
use common\models\FoodPreservation;
use common\models\Product;
use common\models\ProductSeason;
use common\models\Recipes;
use common\models\RegulationPolicy;
use common\models\Season;
use common\models\SlideShow;
use common\models\Tag;
use common\models\VietgapStandard;
use frontend\models\ContactForm;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    const STATUS_ACTIVE = 1;

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
        $tag_id = Tag::find()->select('id')->where(['name' => 'new'])->one();
        if (!empty($tag_id['id'])) {
            $new_product_from_tag = (new Query())->select(['product.id as product_id', 'product.name as product_name', 'product.price as product_price', 'product.tax as product_tax'])->from('product')->innerJoin('product_tag', 'product.id = product_tag.product_id')->where(['product.active' => Product::STATUS_ACTIVE, 'product_tag.tag_id' => $tag_id['id']])->all();

        }
        $new_product_from_system = (new Query())->select(['id as product_id', 'name as product_name', 'price as product_price', 'tax as product_tax'])->from('product')->where(['active' => Product::STATUS_ACTIVE])->orderBy(['id' => SORT_DESC])->limit(10)->all();
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
            $season_from = strtotime(date("d-m", $season_item['from']));
            $season_to = strtotime(date("d-m", $season_item['to']));
            $today = strtotime(date("d-m"));
            if ($season_from <= $today && $today <= $season_to) {
                $product_id = ProductSeason::find()->where(['season_id' => $season_item['id']])->all();
                if (!empty($product_id[0]['season_id'])) {
                    foreach ($product_id as $product_item) {
                        $product = (new Query())->select(['id as product_id', 'name as product_name', 'price as product_price', 'tax as product_tax'])->from('product')->where(['active' => Product::STATUS_ACTIVE, 'id' => $product_item['product_id']])->one();
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
        $slide_show = (new Query())->select(['slide_show.id as slide_show_id', 'slide_show.path as slide_show_path', 'product.name as product_name'])->from('slide_show')->leftJoin('product', 'slide_show.product_id = product.id')->where(['slide_show.active' => SlideShow::STATUS_ACTIVE])->all();

        //get number wishlist
        /* $number_product = WishList::find()->where(['customer_id'=>Yii::$app->user->identity->getId()])->count();
         echo $number_product;*/
        return $this->render('index', [
            'slide_show' => $slide_show, 'new_product' => $new_product,
            'product_season' => $product_season,
        ]);
    }
    public function actionFaq(){
        $faq_query = Faq::find()->select(['question','answer'])->where(['active'=>self::STATUS_ACTIVE]);
        $countQuery = clone $faq_query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
        $faq = $faq_query->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('faq',['faq'=>$faq,'pagination'=>$pagination]);
    }
    public function actionFoodPreservation(){
        if(!empty($_GET['fp'])){
            $food_preservation = FoodPreservation::find()->select(['title','alias','full_post'])->where(['alias'=>$_GET['fp']])->one();
            return $this->render('foodPreservation',['food_preservation'=>$food_preservation]);
        }
        $food_preservation_query = FoodPreservation::find()->select(['title','alias','image','post_info'])->where(['active'=>self::STATUS_ACTIVE]);
        $countQuery = clone $food_preservation_query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
        $food_preservation = $food_preservation_query->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('foodPreservation',['food_preservation'=>$food_preservation,'pagination'=>$pagination]);
    }
    public function actionRecipes(){
        if(!empty($_GET['recipes'])){
            $recipes = Recipes::find()->select(['title','alias','full_post'])->where(['alias'=>$_GET['recipes']])->one();
            return $this->render('recipes',['recipes'=>$recipes]);
        }
        $recipes_query = Recipes::find()->select(['title','alias','image','post_info'])->where(['active'=>self::STATUS_ACTIVE]);
        $countQuery = clone $recipes_query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
        $recipes = $recipes_query->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('recipes',['recipes'=>$recipes,'pagination'=>$pagination]);
    }
    public function actionRegulationPolicy(){
        if(!empty($_GET['rp'])){
            $regulation_policy = RegulationPolicy::find()->select(['title','alias','full_post'])->where(['alias'=>$_GET['rp']])->one();
            return $this->render('regulationPolicy',['regulation_policy'=>$regulation_policy]);
        }
        $regulation_policy_query = RegulationPolicy::find()->select(['title','alias','image','post_info'])->where(['active'=>self::STATUS_ACTIVE]);
        $countQuery = clone $regulation_policy_query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
        $regulation_policy = $regulation_policy_query->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('regulationPolicy',['regulation_policy'=>$regulation_policy,'pagination'=>$pagination]);
    }
    public function actionVietgapStandard(){
        if(!empty($_GET['vs'])){
            $vietgap_standard = VietgapStandard::find()->select(['title','alias','full_post'])->where(['alias'=>$_GET['vs']])->one();
            return $this->render('vietgapStandard',['vietgap_standard'=>$vietgap_standard]);
        }
        $vietgap_standard_query = VietgapStandard::find()->select(['title','alias','image','post_info'])->where(['active'=>self::STATUS_ACTIVE]);
        $countQuery = clone $vietgap_standard_query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
        $vietgap_standard = $vietgap_standard_query->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('vietgapStandard',['vietgap_standard'=>$vietgap_standard,'pagination'=>$pagination]);
    }
    public function actionContact(){
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
    public function actionAboutFreshGarden(){

    }
}
