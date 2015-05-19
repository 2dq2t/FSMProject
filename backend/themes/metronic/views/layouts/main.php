<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
    <!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
    <!--[if !IE]><!-->
    <html lang="<?= Yii::$app->language ?>">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <?= $this->head() ?>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <!--    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">-->
        <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
        <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
        <link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="metronic/assets/pages/css/login.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME STYLES -->
        <link href="metronic/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/admin/css/layout.css" rel="stylesheet" type="text/css"/>
        <link id="style_color" href="metronic/assets/admin/css/themes/light.css" rel="stylesheet" type="text/css"/>
        <link href="metronic/assets/admin/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
        <script src="metronic/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <link rel="shortcut icon" href="favicon.ico"/>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <?php $this->beginBody() ?>
    <?php
    if (!Yii::$app->user->isGuest) {
        ?>
        <body class="page-header-fixed page-sidebar-closed-hide-logo">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.html">
                        <img src="metronic/assets/admin/img/logo-light.png" alt="logo" class="logo-default"/>
                    </a>
                    <div class="menu-toggler sidebar-toggler">
                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN PAGE TOP -->
                <div class="page-top">
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <li class="separator hide">
                            </li>
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <li class="dropdown dropdown-user dropdown-dark">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<span class="username username-hide-on-mobile">
						<?= Yii::$app->user->identity->full_name ?> </span>
                                    <?= Html::img('uploads/employees/image/' . Yii::$app->user->identity->id . '/' . Yii::$app->user->identity->image, ['class' => 'img-circle'])?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="extra_profile.html">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li class="divider">
                                    </li>
                                    <li>
                                        <?= Html::a('<i class="icon-key"></i> Log Out', ['/site/logout'], ['data-method'=>'post']); ?>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END PAGE TOP -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <?php
        function active($catalog, $value = '') {
            $cataloguae = [
                'ecommerce' => [
                    'category',
                    'product',
                    'rating',
                    'unit',
                    'season',
                    'customers',
                    'guest',
                    'order',
                    'orderstatus'
                ],
                'slideshow' => [
                    'slideshow',
                ],
                'users' => [
                    'customer'
                ],
                'employees' => [
                    'employee'
                ],
                'faqs' => [
                    'faq'
                ],
                'marketing' => [
                    'product_offer',
                    'voucher',
                    'vouchertype',
                    'offer',
                    'mail',
                ],
                'settings' => [
                    'file',
                    'backup'
                ]
            ];

            $controller = Yii::$app->controller->id;

            if($value == '') {
                if(array_key_exists($catalog, $cataloguae) && in_array($controller, $cataloguae[$catalog])) {
                    return true;
                }
                return false;
            } else {
                if($value == $controller && in_array($value, $cataloguae[$catalog])){
                    return true;
                }
                return false;
            }
        }

        ?>
        <div class="clearfix">
        </div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <li class="start ">
                            <a href="index.html">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>


                        <li class="<?= active('ecommerce', '') ? 'active open' : '' ?>">
                            <a href="javascript:;">
                                <i class="icon-basket"></i>
                                <span class="title">eCommerce</span>
                                <span class="arrow <?= active('ecommerce', '') ? 'open' : '' ?>"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="ecommerce_index.html">
                                        <i class="icon-home"></i>
                                        Dashboard</a>
                                </li>
                                <li class="<?= active('ecommerce', 'category') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-tag"></i> Categories', ['category/index']) ?>
                                </li>
                                <li class="<?= active('ecommerce', 'order') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-tag"></i> Order', ['order/index']) ?>
                                </li>
                                <li class="<?= active('ecommerce', 'orderstatus') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-notebook"></i> Order Status', ['orderstatus/index']) ?>
                                </li>
                                <li class="<?= active('ecommerce', 'rating') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-star"></i> Rating', ['rating/index']) ?>
                                </li>
                                <li class="<?= active('ecommerce', 'product') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-handbag"></i> Products', ['product/index']) ?>
                                </li>
                                <li class="<?= active('ecommerce', 'unit') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-list"></i> Unit', ['unit/index']) ?>
                                </li>
                                <li class="<?= active('ecommerce', 'season') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-grid"></i> Season', ['season/index']) ?>
                                </li>
                                <li class="<?= active('ecommerce', 'guest') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-user"></i> Customers', ['guest/index']) ?>
                                </li>
                            </ul>
                        </li>
                        <li class="<?= active('users', 'customer') ? 'active' : '' ?>">
                            <a href="index.php?r=customer%2Findex">
                                <i class="icon-users"></i>
                                <span class="title">Users</span>
                            </a>
                        </li>
                        <li class="<?= active('employees', 'employee') ? 'active' : '' ?>">
                            <a href="index.php?r=employee%2Findex">
                                <i class="icon-book-open"></i>
                                <span class="title">Employee</span>
                            </a>
                        </li>
                        <li class="<?= active('slideshow', 'slideshow') ? 'active' : '' ?>">
                            <a href="index.php?r=slideshow%2Findex">
                                <i class="icon-crop"></i>
                                <span class="title">Slide Show</span>
                            </a>
                        </li>
                        <li class="<?= active('faqs', 'faq') ? 'active' : '' ?>">
                            <a href="index.php?r=faq%2Findex">
                                <i class="icon-support"></i>
                                <span class="title">FAQs</span>
                            </a>
                        </li>
                        <li class="<?= active('marketing', '') ? 'active open' : '' ?>">
                            <a href="javascript:;">
                                <i class="icon-basket"></i>
                                <span class="title">Marketing</span>
                                <span class="arrow <?= active('marketing', '') ? 'open' : '' ?>"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="<?= active('marketing', 'voucher') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-wallet"></i> Voucher', ['voucher/index']) ?>
                                </li>
                                <li class="<?= active('marketing', 'vouchertype') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-puzzle"></i> Voucher Type', ['vouchertype/index']) ?>
                                </li>
                                <li class="<?= active('marketing', 'offer') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-share-alt"></i> Product Offer', ['offer/index']) ?>
                                </li>
                                <li class="<?= active('marketing', 'mail') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-envelope-open"></i> Mail', ['mail/index']) ?>
                                </li>
                            </ul>
                        </li>
                        <li class="<?= active('settings', '') ? 'active open' : '' ?>">
                            <a href="javascript:;">
                                <i class="icon-settings"></i>
                                <span class="title">Settings</span>
                                <span class="arrow <?= active('setting', '') ? 'open' : '' ?>"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="<?= active('settings', 'i18n') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-globe"></i> I18n', ['i18n/index']) ?>
                                </li>
                                <li class="<?= active('settings', 'file') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-layers"></i> Data', ['file/index']) ?>
                                </li>
                                <li class="<?= active('settings', 'backup') ? 'active' : '' ?>">
                                    <?= HtmL::a('<i class="icon-layers"></i> Backup', ['backup/default/index']) ?>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    Widget settings form goes here
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn blue">Save changes</button>
                                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN PAGE HEAD -->
                    <div class="page-head">
                        <!-- BEGIN PAGE TITLE -->
                        <div class="page-title">
                            <h1><?= $this->title ?>
                                <!--                        <small>blank page</small>-->
                            </h1>
                        </div>
                        <!-- END PAGE TITLE -->
                    </div>
                    <!-- END PAGE HEAD -->
                    <!-- BEGIN PAGE BREADCRUMB -->
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'itemTemplate' => '<li>{link}<i class="fa fa-circle"></i></li>',
                    ]) ?>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN PAGE CONTENT-->
                    <div class="row">
                        <div class="col-md-12">
                            <?= $content ?>
                        </div>
                    </div>
                    <!-- END PAGE CONTENT-->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner">
                2014 &copy; Metronic by keenthemes.
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        </body>
    <?php
    } else {
        ?>
        <body class="login">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="menu-toggler sidebar-toggler">
        </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="index.html">
                <img src="metronic/assets/admin/img/logo-big.png" alt=""/>
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <?= $content ?>
        </div>
        <div class="copyright">
            2014 © Metronic. Admin Dashboard Template.
        </div>
        <!-- END LOGIN -->
        </body>
        <!-- END BODY -->
    <?php }?>


    <?php $this->endBody() ?>

    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>

    <script src="metronic/assets/global/plugins/respond.min.js"></script>
    <script src="metronic/assets/global/plugins/excanvas.min.js"></script>
    <![endif]-->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!--    <script src="metronic/assets/global/plugins/jquery.min.js" type="text/javascript"></script>-->
    <script src="metronic/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <!--    <script src="metronic/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>-->
    <script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <script src="metronic/assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="metronic/assets/admin/scripts/layout.js" type="text/javascript"></script>
    <script src="metronic/assets/pages/scripts/login.js" type="text/javascript"></script>
    <!--<script src="metronic/assets/admin/scripts/demo.js" type="text/javascript"></script>-->
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            Login.init();
            $("input[name='quantity']").TouchSpin();
        });
    </script>
    <!-- END JAVASCRIPTS -->
    </html>
<?php $this->endPage() ?>