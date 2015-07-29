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
                    <h5>Information</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/information&amp;information_id=4">About
                                Us</a></li>
                        <li>
                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/information&amp;information_id=6">Delivery
                                Information</a></li>
                        <li>
                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/information&amp;information_id=3">Privacy
                                Policy</a></li>
                        <li>
                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/information&amp;information_id=5">Terms
                                &amp; Conditions</a></li>
                    </ul>
                </div>
                <div class=" column">
                    <h5>My Account</h5>
                    <ul class="list-unstyled">
                        <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account">My
                                Account</a></li>
                        <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/order">Order
                                History</a></li>
                        <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/wishlist">Wish
                                List</a></li>
                        <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/newsletter">Newsletter</a>
                        </li>
                    </ul>
                </div>

                <div class="custom_footer_main">
                    <div class="content_footer_right">
                        <div class="column contact"><h5>Contact Us</h5>
                            <ul>
                                <li>
                                    <div class="content">My Company<br>42 avenue des Champs<br>Elysées 75000 Paris<br>Tel
                                        0123-456-789<br>Email <a href="#">sales@company.com</a></div>
                                </li>
                            </ul>
                        </div>
                        <div class="column">
                            <div class="social_block"><h5>Follow us</h5>
                                <ul>
                                    <li class="facebook"><a href="#">Facebook</a></li>
                                    <li class="twitter"><a href="#">Twitter</a></li>
                                    <li class="rss"><a href="#">RSS</a></li>
                                </ul>
                            </div>
                            <div class="paiement_logo_block"><h5>Payment Block</h5>
                                <ul>
                                    <li class="payment_1"><a href=""><img alt=""
                                                                          src="http://opencart-demos.org/OPC05/OPC050107/image/catalog/paymenticon1.jpg"></a>
                                    </li>
                                    <li class="payment_2"><a href=""><img alt=""
                                                                          src="http://opencart-demos.org/OPC05/OPC050107/image/catalog/paymenticon2.jpg"></a>
                                    </li>
                                    <li class="payment_3"><a href=""><img alt=""
                                                                          src="http://opencart-demos.org/OPC05/OPC050107/image/catalog/paymenticon3.jpg"></a>
                                    </li>
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
            <ul>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/special">Specials</a>
                </li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=affiliate/account">Affiliates</a>
                </li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/voucher">Gift
                        Vouchers</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/manufacturer">Brands</a>
                </li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/return/add">Returns</a>
                </li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/sitemap">Site Map</a>
                </li>
                <li class="login-logout"><a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/contact">Contact
                        Us</a></li>
            </ul>
        </div>
        <div class="copy-right">Powered By <a href="http://www.opencart.com">OpenCart</a> Your Store &copy; 2015</div>
    </div>
</footer>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
