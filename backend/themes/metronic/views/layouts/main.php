<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <!--[if IE 8]> <html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
    <!--[if IE 9]> <html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
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
        <!--        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">-->
        <link href="metronic/assets/global/css/opensans/opensans.css" rel="stylesheet" type="text/css">
        <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
        <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
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
        <link href="metronic/assets/pages/css/prettify.css" rel="stylesheet" type="text/css"/>
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
                    <a href="<?= \yii\helpers\Url::home()?>">
                        <img src="metronic/assets/admin/img/logo-big-freshgarden.png" alt="logo" class="logo-default"/>
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
                                        <a href="<?= $baseUrl."/index.php?r=account/update&id=". Yii::$app->user->identity->id; ?>">
                                            <i class="icon-user"></i> <?=Yii::t('app', 'My Profile')?> </a>
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

//        function isActive($routes = array())
//        {
//            $routeCurrent = '';
//            if (Yii::$app->controller->module !== null) {
//                $routeCurrent .= sprintf('%s/', Yii::$app->controller->module->id);
//            }
//            $routeCurrent .= sprintf('%s/%s', Yii::$app->controller->id, Yii::$app->controller->action->id);
//            foreach ($routes as $route) {
//                $pattern = sprintf('~\b%s\b~', preg_quote($route));
//                if (preg_match($pattern, $routeCurrent)) {
//                    return true;
//                }
//            }
//            return false;
//        }

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

                        <?php
                        $s = [
                            [
                                'url' => ['site/index'],
                                'label' => Yii::t('app', 'Dashboard'),
                                'icon' => 'icon-home',
                                'items' => []
                            ],
                            [
                                'label' => Yii::t('app', 'Marketing'),
                                'icon' => 'icon-bar-chart',
                                'items' => [
                                    [
                                        'url' => ['faq/index', 'faq/create', 'faq/update'],
                                        'label' => Yii::t('app', 'FAQs'),
                                        'icon' => 'icon-support',
                                    ],
                                    [
                                        'url' => ['voucher/index', 'voucher/create', 'voucher/update'],
                                        'label' => Yii::t('app', 'Voucher'),
                                        'icon' => 'icon-wallet'
                                    ],
                                    [
                                        'url' => ['offer/index', 'offer/create', 'offer/update'],
                                        'label' => Yii::t('app', 'Product Offer'),
                                        'icon' => 'icon-share-alt'
                                    ],
                                    [
                                        'url' => ['slideshow/index', 'slideshow/create', 'slideshow/update'],
                                        'label' => Yii::t('app', 'Slide Shows'),
                                        'icon' => 'icon-crop'
                                    ],
                                    [
                                        'url' => ['email/index', 'email/sendemail'],
                                        'label' => Yii::t('app', 'Mail'),
                                        'icon' => 'icon-envelope-open'
                                    ],
                                    [
                                        'url' => ['foodpreservation/index', 'foodpreservation/create', 'foodpreservation/update'],
                                        'label' => Yii::t('app', 'Food Preservations'),
                                        'icon' => 'icon-bag'
                                    ],
                                    [
                                        'url' => ['recipes/index', 'recipes/create', 'recipes/update'],
                                        'label' => Yii::t('app', 'Recipes'),
                                        'icon' => 'icon-note'
                                    ],
                                    [
                                        'url' => ['regulationpolicy/index', 'regulationpolicy/create', 'regulationpolicy/update'],
                                        'label' => Yii::t('app', 'Regulation Policies'),
                                        'icon' => 'icon-share'
                                    ],
                                    [
                                        'url' => ['vietgapstandard/index', 'vietgapstandard/create', 'vietgapstandard/update'],
                                        'label' => Yii::t('app', 'Vietgap Standards'),
                                        'icon' => 'icon-link'
                                    ],
                                ]
                            ],
                            [
                                'label' => Yii::t('app', 'Business'),
                                'icon' => 'icon-present',
                                'items' => [
                                    [
                                        'url' => ['category/index', 'category/create', 'category/update'],
                                        'label' => Yii::t('app', 'Categories'),
                                        'icon' => 'icon-tag',
                                    ],
                                    [
                                        'url' => ['unit/index', 'unit/create', 'unit/update'],
                                        'label' => Yii::t('app', 'Unit'),
                                        'icon' => 'icon-list',
                                    ],
                                    [
                                        'url' => ['season/index', 'season/create', 'season/update'],
                                        'label' => Yii::t('app', 'Season'),
                                        'icon' => 'icon-grid'
                                    ],
                                    [
                                        'url' => ['tag/index', 'tag/create', 'tag/update'],
                                        'label' => Yii::t('app', 'Tags'),
                                        'icon' => 'icon-pin'
                                    ],
                                    [
                                        'url' => ['rating/index', 'rating/create', 'rating/update'],
                                        'label' => Yii::t('app', 'Rating'),
                                        'icon' => 'icon-star'
                                    ],
                                    [
                                        'url' => ['product/index', 'product/create', 'product/update'],
                                        'label' => Yii::t('app', 'Product'),
                                        'icon' => 'icon-handbag'
                                    ]
                                ]
                            ],
                            [
                                'label' => Yii::t('app', 'Sale'),
                                'icon' => 'icon-basket',
                                'items' => [
                                    [
                                        'url' => ['order/index', 'order/create', 'order/update'],
                                        'label' => Yii::t('app', 'Order'),
                                        'icon' => 'icon-tag'
                                    ],
                                    [
                                        'url' => ['orderstatus/index', 'orderstatus/create', 'orderstatus/update'],
                                        'label' => Yii::t('app', 'Order Status'),
                                        'icon' => 'icon-notebook'
                                    ]
                                ]
                            ],
                            [
                                'label' => Yii::t('app', 'Employee'),
                                'icon' => 'icon-user',
                                'items' => [
                                    [
                                        'url' => ['employee/index', 'employee/create', 'employee/update'],
                                        'label' => Yii::t('app', 'EmployeeInfo'),
                                        'icon' => 'icon-book-open'
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Authorization'),
                                        'icon' => 'icon-energy',
                                        'items' => [
                                            [
                                                'url' => ['assignment/index', 'assignment/create', 'assignment/update'],
                                                'label' => Yii::t('app', 'Assignment'),
                                                'icon' => 'icon-login'
                                            ],
                                            [
                                                'url' => ['role/index', 'role/create', 'role/update'],
                                                'label' => Yii::t('app', 'Role'),
                                                'icon' => 'icon-users'
                                            ],
                                            [
                                                'url' => ['route/index', 'route/create', 'route/assign'],
                                                'label' => Yii::t('app', 'Route'),
                                                'icon' => 'icon-reload'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                'label' => Yii::t('app', 'Customer'),
                                'icon' => 'icon-users',
                                'items' => [
                                    [
                                        'url' => ['guest/index', 'guest/create', 'guest/update'],
                                        'label' => Yii::t('app', 'Guest'),
                                        'icon' => 'icon-notebook'
                                    ],
                                    [
                                        'url' => ['customer/index', 'customer/create', 'customer/update'],
                                        'label' => Yii::t('app', 'Customers'),
                                        'icon' => 'icon-book-open'
                                    ]
                                ]
                            ],
                            [
                                'label' => Yii::t('app', 'Settings'),
                                'icon' => 'icon-settings',
                                'items' => [
                                    [
                                        'url' => ['i18n/index', 'i18n/update'],
                                        'label' => Yii::t('app', 'I18n'),
                                        'icon' => 'icon-globe'
                                    ],
                                    [
                                        'url' => ['backup/index', 'backup/create', 'backup/delete', 'backup/restore', 'backup/upload'],
                                        'label' =>  Yii::t('app', 'Backup'),
                                        'icon' => 'icon-layers'
                                    ],
                                    [
                                        'url' => ['log/index', 'log/index', 'log/view', 'log/delete', 'log/download'],
                                        'label' => Yii::t('app', 'Log'),
                                        'icon' => 'icon-note'
                                    ]
                                ]
                            ]
                        ];

                        echo \backend\widgets\Menu::widget([
                            'items' => $s
                        ])

                        ?>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <div class="page-content">
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
                <?= date('Y') . ' ' . Yii::t('app', 'FreshGarden')?>
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
            <a href="<?= \yii\helpers\Url::home()?>">
                <img src="metronic/assets/admin/img/Logo-.big.png" alt=""/>
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <?= $content ?>
        </div>
        <div class="copyright">
            <?= date('Y') . ' ' . Yii::t('app', 'FreshGarden')?>
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
    <!-- END CORE PLUGINS -->
    <!--    <link href="http://www.wprunner.com/wp-content/themes/wprunner-v2/assets/css/prettify.css" rel="stylesheet">-->
    <script src="metronic/assets/pages/scripts/prettify.js"></script>
    <script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="metronic/assets/admin/scripts/layout.js" type="text/javascript"></script>
    <script src="metronic/assets/admin/scripts/yii.admin.js" type="text/javascript"></script>
    <script src="metronic/assets/pages/scripts/login.js" type="text/javascript"></script>
    <!--<script src="metronic/assets/admin/scripts/demo.js" type="text/javascript"></script>-->
    <script type="text/javascript">
        !function ($) {
            $(function(){
                window.prettyPrint && prettyPrint()
            })
        }(window.jQuery)
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            Login.init();
        });
    </script>
    <!-- END JAVASCRIPTS -->
    </html>
<?php $this->endPage() ?>