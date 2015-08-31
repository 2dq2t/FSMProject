<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\widgets\Breadcrumbs;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]>
<html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]>
<html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="My Store"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="<?= Yii::$app->request->baseUrl."/js/jquery/jquery-2.1.1.min.js"?>"></script>
    <script src="<?= Yii::$app->request->baseUrl."/js/bootstrap/bootstrap.min.js"?>"></script>
    <script src="<?= Yii::$app->request->baseUrl."/js/jquery/owl-carousel/owl.carousel.min.js"?>"></script>
    <script src="<?= Yii::$app->request->baseUrl."/js/common.js"?>"></script>
    <link rel="icon" type="image/png" href="images/favicon-32x32.png">
</head>
<body class="common-home layout-2 left-col">
<div id="fb-root"></div>
<?php $this->beginBody() ?>
<?php
$baseUrl = Yii::$app->request->baseUrl;
?>


<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
<?= Alert::widget() ?>
<?= $content ?>

<footer>
    <div class="footer-inner">
        <div id="footer" class="container">
            <div>
                <div class="column">
                    <h5><?= Yii::t('app','Index Information')?></h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="<?=Yii::$app->request->baseUrl."/index.php?r=site/about-fresh-garden"?>"><?=Yii::t('app','AboutFreshGarden')?>
                            </a></li>
                        <li>
                            <a href="<?=Yii::$app->request->baseUrl."/index.php?r=site/faq"?>"><?= Yii::t('app','FAQs')?></a></li>
                        <li>
                            <a href="<?=Yii::$app->request->baseUrl."/index.php?r=site/regulation-policy"?>"><?= Yii::t('app','RegulationPolicy')?></a></li>

                    </ul>
                </div>
                <div class=" column">
                    <h5><?=Yii::t('app','MyAccountLabel')?></h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= Yii::$app->request->baseUrl ."/index.php?r=account/manageacc"?>" class="list-group-item"><?=Yii::t('app','MyAccountLabel')?></a></li>
                        <li><a href="<?= Yii::$app->request->baseUrl . "/index.php?r=account/get-order-history"?>"><?=Yii::t('app','MyOrderLabel')?></a></li>
                        <li><a href="<?= Yii::$app->request->baseUrl . "/index.php?r=wish-list/get-wish-list" ?>"><?= Yii::t('app', 'WishListLabel') ?> </a></li>
                    </ul>
                </div>

                <div class="custom_footer_main">
                    <div class="content_footer_right">
                        <div class="column contact"><h5><?=Yii::t('app','Index ContactAddress')?></h5>
                            <ul>
                                <li>
                                    <div class="content"><?=Yii::t('app','Content ContactAddress')?></div>
                                </li>
                            </ul>
                        </div>
                        <div class="column">
                            <div class="social_block"><h5><?=Yii::t('app','FollowFreshGarden')?></h5>
                                <ul>
                                    <li class="facebook"><a href="https://www.facebook.com/freshgarden2015"><?=Yii::t('app','Facebook')?></a></li>
                                    <li class="twitter"><a href="#"><?=Yii::t('app','Twitter')?></a></li>
                                    <li class="rss"><a href="#"><?=Yii::t('app','RSS')?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div id="main_footer">
        <div id="bottomfooter">

        </div>
        <div class="copy-right">Powered By <a href="<?=Yii::$app->request->baseUrl?>">FreshGarden</a> &copy; 2015</div>
    </div>
</footer>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
