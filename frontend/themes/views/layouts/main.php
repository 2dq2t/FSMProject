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
    <script src="/fsmproject/frontend/web/js/jquery/jquery-2.1.1.min.js"></script>
    <script src="/fsmproject/frontend/web/js/jquery/owl-carousel/owl.carousel.min.js"></script>
    <script src="/FSMProject/frontend/web/js/common.js"></script>
</head>
<body class="common-home layout-2 left-col">
<?php $this->beginBody() ?>
<?php
$baseUrl = Yii::$app->request->baseUrl;
?>
<header>
    <div class="header">
        <div id="header-top" class="container">
            <div class="header-top">

            </div>
        </div>

        <div class="container">
            <div class="row">

                <div class="col-sm-4 header-logo">
                    <div id="logo">
                        <a href="<?php echo $baseUrl?>"><img
                                src="images/data/logo.png"
                                title="Your Store" alt="Your Store" class="img-responsive"/></a>
                    </div>
                </div>
                <div class="header-right">
                    <div class="col-sm-5 header-search">
                        <div id="search" class="input-group">
                            <input type="text" name="search" value="" placeholder="Search"
                                   class="form-control input-lg"/>
                              <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg"></button>
                              </span>
                        </div>
                    </div>
                    <div class="col-sm-3 header-cart">
                        <div id="cart" class="btn-group btn-block">
                            <button type="button" data-toggle="dropdown" data-loading-text="Loading..."
                                    class="btn btn-inverse btn-block btn-lg dropdown-toggle"><span id="cart-total">0 item(s) - $0.00</span>
                            </button>
                            <ul class="dropdown-menu pull-right cart-menu">
                                <li>
                                    <p class="text-center">Your shopping cart is empty!</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="nav-container" role="navigation">
    <div class="nav-inner">
        <!-- ======= Menu Code START ========= -->
        <!-- Opencart 3 level Category Menu-->
        <div class="container">
            <div id="menu" class="main-menu">

                <div class="nav-responsive"><span>Menu</span>

                    <div class="expandable"></div>
                </div>
                <ul class="main-navigation">
                    <li>
                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20">Electronics</a>

                        <ul>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20_59">Laptops</a>

                            </li>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20_60">Tablates</a>

                            </li>

                            <li>
                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20_26"
                                   class="activSub">PC</a>
                                <ul class="col4">
                                    <li>
                                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=26_61">Dell</a>
                                    </li>
                                    <li>
                                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=26_62">Samsung</a>
                                    </li>
                                </ul>

                            </li>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20_27">Mac</a>

                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=18">Parts
                            &amp; Accessories</a>

                        <ul>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=18_46">Macs</a>

                            </li>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=18_45">Windows</a>

                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=25">Components</a>

                        <ul>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=25_29">Mice
                                    and Trackballs</a>

                            </li>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=25_28">Monitors</a>

                            </li>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=25_30">Printers</a>

                            </li>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=25_31">Scanners</a>

                            </li>

                            <li>

                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=25_32">Web
                                    Cameras</a>

                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=57">Interiors</a>
                    </li>
                    <li>
                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=17">Kitchen</a>
                    </li>
                    <li>
                        <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=24">House
                            Holds</a>
                    </li>
                </ul>
            </div>
            <nav id="top">
                <div class="container">
                    <div id="top-links" class="nav">
                        <ul class="list-inline">
                            <?php if(Yii::$app->user->isGuest): ?>
                                <li class="dropdown myaccount"><a
                                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account"
                                        title="My Account" class="dropdown-toggle"
                                        data-toggle="dropdown"><span>My Account</span> <span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right myaccount-menu">
                                        <li>
                                            <a href="index.php?r=site/register">Register</a>
                                        </li>
                                        <li>
                                            <a href="index.php?r=site/login">Login</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="dropdown myaccount"><a
                                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account"
                                        title="My Account" class="dropdown-toggle"
                                        data-toggle="dropdown"><span><?= Yii::$app->user->identity->username ?></span> <span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right myaccount-menu">
                                        <li>
                                            <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id; ?>">Tài khoản của tôi</a>
                                        </li>
                                        <li>
                                            <a href="#">Đơn hàng của tôi</a>
                                        </li>
                                        <li>
                                            <a href="#">Sản phẩm yêu thích</a>
                                        </li>
                                        <li>
                                            <a href="<?= $baseUrl."/index.php?r=site/logout" ?>" data-method="post">Thoát</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/wishlist"
                                   id="wishlist-total" title="Wish List (0)"> <span>Wish List (0)</span></a></li>
                            <li class="checkout"><a
                                    href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=checkout/checkout"
                                    title="Checkout"> <span>Checkout</span></a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- ======= Menu Code END ========= -->
        </div>
    </div>
</nav>

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

<<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3&appId=369040309965950";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</html>
<?php $this->endPage() ?>
