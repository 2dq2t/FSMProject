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
    <!-- BEGIN THEME STYLES -->
    <link href="metronic/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/admin/css/layout.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="metronic/assets/admin/css/themes/light.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/admin/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<?php $this->beginBody() ?>
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
        <!-- BEGIN PAGE ACTIONS -->
        <div class="page-actions">
            <div class="btn-group">
                <button type="button" class="btn red-haze btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <span class="hidden-sm hidden-xs">Actions&nbsp;</span><i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="#">
                            <i class="icon-docs"></i> New Post </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-tag"></i> New Comment </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-share"></i> Share </a>
                    </li>
                    <li class="divider">
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-flag"></i> Comments <span class="badge badge-success">4</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-users"></i> Feedbacks <span class="badge badge-danger">2</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END PAGE ACTIONS -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
            <form class="search-form" action="extra_search.html" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" placeholder="Search..." name="query">
					<span class="input-group-btn">
					<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
					</span>
                </div>
            </form>
            <!-- END HEADER SEARCH BOX -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="separator hide">
                    </li>
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-bell"></i>
						<span class="badge badge-success">
						7 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3><span class="bold">12 pending</span> notifications</h3>
                                <a href="extra_profile.html">view all</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">just now</span>
										<span class="details">
										<span class="label label-sm label-icon label-success">
										<i class="fa fa-plus"></i>
										</span>
										New user registered. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">3 mins</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										Server #12 overloaded. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">10 mins</span>
										<span class="details">
										<span class="label label-sm label-icon label-warning">
										<i class="fa fa-bell-o"></i>
										</span>
										Server #2 not responding. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">14 hrs</span>
										<span class="details">
										<span class="label label-sm label-icon label-info">
										<i class="fa fa-bullhorn"></i>
										</span>
										Application error. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">2 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										Database overloaded 68%. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">3 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										A user IP blocked. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">4 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-warning">
										<i class="fa fa-bell-o"></i>
										</span>
										Storage Server #4 not responding dfdfdfd. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">5 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-info">
										<i class="fa fa-bullhorn"></i>
										</span>
										System Error. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">9 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										Storage server failed. </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- END NOTIFICATION DROPDOWN -->
                    <li class="separator hide">
                    </li>
                    <!-- BEGIN INBOX DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended dropdown-inbox dropdown-dark" id="header_inbox_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-envelope-open"></i>
						<span class="badge badge-danger">
						4 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3>You have <span class="bold">7 New</span> Messages</h3>
                                <a href="inbox.html">view all</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                    <li>
                                        <a href="inbox.html?a=view">
										<span class="photo">
										<img src="../../assets/admin/layout3/img/avatar2.jpg" class="img-circle" alt="">
										</span>
										<span class="subject">
										<span class="from">
										Lisa Wong </span>
										<span class="time">Just Now </span>
										</span>
										<span class="message">
										Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="inbox.html?a=view">
										<span class="photo">
										<img src="../../assets/admin/layout3/img/avatar3.jpg" class="img-circle" alt="">
										</span>
										<span class="subject">
										<span class="from">
										Richard Doe </span>
										<span class="time">16 mins </span>
										</span>
										<span class="message">
										Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="inbox.html?a=view">
										<span class="photo">
										<img src="../../assets/admin/layout3/img/avatar1.jpg" class="img-circle" alt="">
										</span>
										<span class="subject">
										<span class="from">
										Bob Nilson </span>
										<span class="time">2 hrs </span>
										</span>
										<span class="message">
										Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="inbox.html?a=view">
										<span class="photo">
										<img src="../../assets/admin/layout3/img/avatar2.jpg" class="img-circle" alt="">
										</span>
										<span class="subject">
										<span class="from">
										Lisa Wong </span>
										<span class="time">40 mins </span>
										</span>
										<span class="message">
										Vivamus sed auctor 40% nibh congue nibh... </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="inbox.html?a=view">
										<span class="photo">
										<img src="../../assets/admin/layout3/img/avatar3.jpg" class="img-circle" alt="">
										</span>
										<span class="subject">
										<span class="from">
										Richard Doe </span>
										<span class="time">46 mins </span>
										</span>
										<span class="message">
										Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- END INBOX DROPDOWN -->
                    <li class="separator hide">
                    </li>
                    <!-- BEGIN TODO DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark" id="header_task_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-calendar"></i>
						<span class="badge badge-primary">
						3 </span>
                        </a>
                        <ul class="dropdown-menu extended tasks">
                            <li class="external">
                                <h3>You have <span class="bold">12 pending</span> tasks</h3>
                                <a href="page_todo.html">view all</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                    <li>
                                        <a href="javascript:;">
										<span class="task">
										<span class="desc">New release v1.2 </span>
										<span class="percent">30%</span>
										</span>
										<span class="progress">
										<span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">40% Complete</span></span>
										</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
										<span class="task">
										<span class="desc">Application deployment</span>
										<span class="percent">65%</span>
										</span>
										<span class="progress">
										<span style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">65% Complete</span></span>
										</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
										<span class="task">
										<span class="desc">Mobile app release</span>
										<span class="percent">98%</span>
										</span>
										<span class="progress">
										<span style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">98% Complete</span></span>
										</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
										<span class="task">
										<span class="desc">Database migration</span>
										<span class="percent">10%</span>
										</span>
										<span class="progress">
										<span style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">10% Complete</span></span>
										</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
										<span class="task">
										<span class="desc">Web server upgrade</span>
										<span class="percent">58%</span>
										</span>
										<span class="progress">
										<span style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">58% Complete</span></span>
										</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
										<span class="task">
										<span class="desc">Mobile development</span>
										<span class="percent">85%</span>
										</span>
										<span class="progress">
										<span style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">85% Complete</span></span>
										</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
										<span class="task">
										<span class="desc">New UI release</span>
										<span class="percent">38%</span>
										</span>
										<span class="progress progress-striped">
										<span style="width: 38%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">38% Complete</span></span>
										</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- END TODO DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<span class="username username-hide-on-mobile">
						Nick </span>
                            <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                            <img alt="" class="img-circle" src="metronic/assets/admin/img/avatar9.jpg"/>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="extra_profile.html">
                                    <i class="icon-user"></i> My Profile </a>
                            </li>
                            <li>
                                <a href="page_calendar.html">
                                    <i class="icon-calendar"></i> My Calendar </a>
                            </li>
                            <li>
                                <a href="inbox.html">
                                    <i class="icon-envelope-open"></i> My Inbox <span class="badge badge-danger">
								3 </span>
                                </a>
                            </li>
                            <li>
                                <a href="page_todo.html">
                                    <i class="icon-rocket"></i> My Tasks <span class="badge badge-success">
								7 </span>
                                </a>
                            </li>
                            <li class="divider">
                            </li>
                            <li>
                                <a href="extra_lock.html">
                                    <i class="icon-lock"></i> Lock Screen </a>
                            </li>
                            <li>
                                <a href="login.html">
                                    <i class="icon-key"></i> Log Out </a>
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
            ],
            'users' => []
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
                        <li>
                            <a href="ecommerce_orders.html">
                                <i class="icon-basket"></i>
                                Orders</a>
                        </li>
                        <li class="<?= active('ecommerce', 'rating') ? 'active' : '' ?>">
                            <?= HtmL::a('<i class="icon-star"></i> Rating', ['rating/index']) ?>
                        </li>
                        <li>
                            <a href="ecommerce_orders_view.html">
                                <i class="icon-tag"></i>
                                Order View</a>
                        </li>
                        <li class="<?= active('ecommerce', 'product') ? 'active' : '' ?>">
                            <?= HtmL::a('<i class="icon-handbag"></i> Products', ['product/index']) ?>
                        </li>
                        <li>
                            <a href="ecommerce_products_edit.html">
                                <i class="icon-pencil"></i>
                                Product Edit</a>
                        </li>
                    </ul>
                </li>
                <li class="<?= active('users', '') ? 'active open' : '' ?>">
                    <a href="javascript:;">
                        <i class="icon-rocket"></i>
                        <span class="title">Page Layouts</span>
                        <span class="arrow <?= active('users', '') ? 'open' : '' ?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="layout_sidebar_reversed.html">
                                <span class="badge badge-warning">new</span>Right Sidebar Page</a>
                        </li>
                        <li>
                            <a href="layout_sidebar_fixed.html">
                                Sidebar Fixed Page</a>
                        </li>
                        <li>
                            <a href="layout_sidebar_closed.html">
                                Sidebar Closed Page</a>
                        </li>
                        <li>
                            <a href="layout_blank_page.html">
                                Blank Page</a>
                        </li>
                        <li>
                            <a href="layout_boxed_page.html">
                                Boxed Page</a>
                        </li>
                        <li>
                            <a href="layout_language_bar.html">
                                Language Switch Bar</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-diamond"></i>
                        <span class="title">UI Features</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="ui_general.html">
                                General Components</a>
                        </li>
                        <li>
                            <a href="ui_buttons.html">
                                Buttons</a>
                        </li>
                        <li>
                            <a href="ui_icons.html">
                                <span class="badge badge-danger">new</span>Font Icons</a>
                        </li>
                        <li>
                            <a href="ui_colors.html">
                                Flat UI Colors</a>
                        </li>
                        <li>
                            <a href="ui_typography.html">
                                Typography</a>
                        </li>
                        <li>
                            <a href="ui_tabs_accordions_navs.html">
                                Tabs, Accordions & Navs</a>
                        </li>
                        <li>
                            <a href="ui_tree.html">
                                <span class="badge badge-danger">new</span>Tree View</a>
                        </li>
                        <li>
                            <a href="ui_page_progress_style_1.html">
                                <span class="badge badge-warning">new</span>Page Progress Bar - Style 1</a>
                        </li>
                        <li>
                            <a href="ui_blockui.html">
                                Block UI</a>
                        </li>
                        <li>
                            <a href="ui_notific8.html">
                                Notific8 Notifications</a>
                        </li>
                        <li>
                            <a href="ui_toastr.html">
                                Toastr Notifications</a>
                        </li>
                        <li>
                            <a href="ui_alert_dialog_api.html">
                                <span class="badge badge-danger">new</span>Alerts & Dialogs API</a>
                        </li>
                        <li>
                            <a href="ui_session_timeout.html">
                                Session Timeout</a>
                        </li>
                        <li>
                            <a href="ui_idle_timeout.html">
                                User Idle Timeout</a>
                        </li>
                        <li>
                            <a href="ui_modals.html">
                                Modals</a>
                        </li>
                        <li>
                            <a href="ui_extended_modals.html">
                                Extended Modals</a>
                        </li>
                        <li>
                            <a href="ui_tiles.html">
                                Tiles</a>
                        </li>
                        <li>
                            <a href="ui_datepaginator.html">
                                <span class="badge badge-success">new</span>Date Paginator</a>
                        </li>
                        <li>
                            <a href="ui_nestable.html">
                                Nestable List</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-puzzle"></i>
                        <span class="title">UI Components</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="components_pickers.html">
                                Date & Time Pickers</a>
                        </li>
                        <li>
                            <a href="components_context_menu.html">
                                Context Menu</a>
                        </li>
                        <li>
                            <a href="components_dropdowns.html">
                                Custom Dropdowns</a>
                        </li>
                        <li>
                            <a href="components_form_tools.html">
                                Form Tools</a>
                        </li>
                        <li>
                            <a href="components_editors.html">
                                Markdown & WYSIWYG Editors</a>
                        </li>
                        <li>
                            <a href="components_ion_sliders.html">
                                Ion Range Sliders</a>
                        </li>
                        <li>
                            <a href="components_noui_sliders.html">
                                NoUI Range Sliders</a>
                        </li>
                        <li>
                            <a href="components_jqueryui_sliders.html">
                                jQuery UI Sliders</a>
                        </li>
                        <li>
                            <a href="components_knob_dials.html">
                                Knob Circle Dials</a>
                        </li>
                    </ul>
                </li>
                <!-- BEGIN ANGULARJS LINK -->
                <li class="tooltips" data-container="body" data-placement="right" data-html="true" data-original-title="AngularJS version demo">
                    <a href="angularjs" target="_blank">
                        <i class="icon-paper-plane"></i>
					<span class="title">
					AngularJS Version </span>
                    </a>
                </li>
                <!-- END ANGULARJS LINK -->
                <li>
                    <a href="javascript:;">
                        <i class="icon-settings"></i>
                        <span class="title">Form Stuff</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="form_controls.html">
                                Form Controls</a>
                        </li>
                        <li>
                            <a href="form_icheck.html">
                                iCheck Controls</a>
                        </li>
                        <li>
                            <a href="form_layouts.html">
                                Form Layouts</a>
                        </li>
                        <li>
                            <a href="form_editable.html">
                                <span class="badge badge-warning">new</span>Form X-editable</a>
                        </li>
                        <li>
                            <a href="form_wizard.html">
                                Form Wizard</a>
                        </li>
                        <li>
                            <a href="form_validation.html">
                                Form Validation</a>
                        </li>
                        <li>
                            <a href="form_image_crop.html">
                                <span class="badge badge-danger">new</span>Image Cropping</a>
                        </li>
                        <li>
                            <a href="form_fileupload.html">
                                Multiple File Upload</a>
                        </li>
                        <li>
                            <a href="form_dropzone.html">
                                Dropzone File Upload</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-briefcase"></i>
                        <span class="title">Data Tables</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="table_basic.html">
                                Basic Datatables</a>
                        </li>
                        <li>
                            <a href="table_tree.html">
                                Tree Datatables</a>
                        </li>
                        <li>
                            <a href="table_responsive.html">
                                Responsive Datatables</a>
                        </li>
                        <li>
                            <a href="table_managed.html">
                                Managed Datatables</a>
                        </li>
                        <li>
                            <a href="table_editable.html">
                                Editable Datatables</a>
                        </li>
                        <li>
                            <a href="table_advanced.html">
                                Advanced Datatables</a>
                        </li>
                        <li>
                            <a href="table_ajax.html">
                                Ajax Datatables</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-wallet"></i>
                        <span class="title">Portlets</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="portlet_general.html">
                                General Portlets</a>
                        </li>
                        <li>
                            <a href="portlet_general2.html">
                                <span class="badge badge-danger">new</span>New Portlets #1</a>
                        </li>
                        <li>
                            <a href="portlet_general3.html">
                                <span class="badge badge-danger">new</span>New Portlets #2</a>
                        </li>
                        <li>
                            <a href="portlet_ajax.html">
                                Ajax Portlets</a>
                        </li>
                        <li>
                            <a href="portlet_draggable.html">
                                Draggable Portlets</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-bar-chart"></i>
                        <span class="title">Charts</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="charts_amcharts.html">
                                Amchart</a>
                        </li>
                        <li>
                            <a href="charts_flotcharts.html">
                                Flotchart</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-docs"></i>
                        <span class="title">Pages</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="page_timeline.html">
                                <i class="icon-paper-plane"></i>
                                <span class="badge badge-warning">2</span>New Timeline</a>
                        </li>
                        <li>
                            <a href="extra_profile.html">
                                <i class="icon-user-following"></i>
                                <span class="badge badge-success badge-roundless">new</span>New User Profile</a>
                        </li>
                        <li>
                            <a href="page_todo.html">
                                <i class="icon-hourglass"></i>
                                <span class="badge badge-danger">4</span>Todo</a>
                        </li>
                        <li>
                            <a href="inbox.html">
                                <i class="icon-envelope"></i>
                                <span class="badge badge-danger">4</span>Inbox</a>
                        </li>
                        <li>
                            <a href="extra_faq.html">
                                <i class="icon-info"></i>
                                FAQ</a>
                        </li>
                        <li>
                            <a href="page_portfolio.html">
                                <i class="icon-feed"></i>
                                Portfolio</a>
                        </li>
                        <li>
                            <a href="page_timeline.html">
                                <i class="icon-clock"></i>
                                <span class="badge badge-info">4</span>Timeline</a>
                        </li>
                        <li>
                            <a href="page_coming_soon.html">
                                <i class="icon-flag"></i>
                                Coming Soon</a>
                        </li>
                        <li>
                            <a href="page_calendar.html">
                                <i class="icon-calendar"></i>
                                <span class="badge badge-danger">14</span>Calendar</a>
                        </li>
                        <li>
                            <a href="extra_invoice.html">
                                <i class="icon-flag"></i>
                                Invoice</a>
                        </li>
                        <li>
                            <a href="page_blog.html">
                                <i class="icon-speech"></i>
                                Blog</a>
                        </li>
                        <li>
                            <a href="page_blog_item.html">
                                <i class="icon-link"></i>
                                Blog Post</a>
                        </li>
                        <li>
                            <a href="page_news.html">
                                <i class="icon-eye"></i>
                                <span class="badge badge-success">9</span>News</a>
                        </li>
                        <li>
                            <a href="page_news_item.html">
                                <i class="icon-bell"></i>
                                News View</a>
                        </li>
                        <li>
                            <a href="page_timeline_old.html">
                                <i class="icon-paper-plane"></i>
                                <span class="badge badge-warning">2</span>Old Timeline</a>
                        </li>
                        <li>
                            <a href="extra_profile_old.html">
                                <i class="icon-user"></i>
                                Old User Profile</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-present"></i>
                        <span class="title">Extra</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="page_about.html">
                                About Us</a>
                        </li>
                        <li>
                            <a href="page_contact.html">
                                Contact Us</a>
                        </li>
                        <li>
                            <a href="extra_search.html">
                                Search Results</a>
                        </li>
                        <li>
                            <a href="extra_pricing_table.html">
                                Pricing Tables</a>
                        </li>
                        <li>
                            <a href="extra_404_option1.html">
                                404 Page Option 1</a>
                        </li>
                        <li>
                            <a href="extra_404_option2.html">
                                404 Page Option 2</a>
                        </li>
                        <li>
                            <a href="extra_404_option3.html">
                                404 Page Option 3</a>
                        </li>
                        <li>
                            <a href="extra_500_option1.html">
                                500 Page Option 1</a>
                        </li>
                        <li>
                            <a href="extra_500_option2.html">
                                500 Page Option 2</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-folder"></i>
                        <span class="title">Multi Level Menu</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="javascript:;">
                                <i class="icon-settings"></i> Item 1 <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        <i class="icon-user"></i>
                                        Sample Link 1 <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#"><i class="icon-power"></i> Sample Link 1</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon-paper-plane"></i> Sample Link 1</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon-star"></i> Sample Link 1</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-camera"></i> Sample Link 1</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-link"></i> Sample Link 2</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-pointer"></i> Sample Link 3</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <i class="icon-globe"></i> Item 2 <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="#"><i class="icon-tag"></i> Sample Link 1</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-pencil"></i> Sample Link 1</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-graph"></i> Sample Link 1</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <i class="icon-bar-chart"></i>
                                Item 3 </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-user"></i>
                        <span class="title">Login Options</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="login.html">
                                Login Form 1</a>
                        </li>
                        <li>
                            <a href="login_2.html">
                                Login Form 2</a>
                        </li>
                        <li>
                            <a href="login_3.html">
                                Login Form 3</a>
                        </li>
                        <li>
                            <a href="login_soft.html">
                                Login Form 4</a>
                        </li>
                        <li>
                            <a href="extra_lock.html">
                                Lock Screen 1</a>
                        </li>
                        <li>
                            <a href="extra_lock2.html">
                                Lock Screen 2</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="icon-envelope-open"></i>
                        <span class="title">Email Templates</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="email_newsletter.html">
                                Responsive Newsletter<br>
                                Email Template</a>
                        </li>
                        <li>
                            <a href="email_system.html">
                                Responsive System<br>
                                Email Template</a>
                        </li>
                    </ul>
                </li>
                <li class="last ">
                    <a href="javascript:;">
                        <i class="icon-pointer"></i>
                        <span class="title">Maps</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="maps_google.html">
                                Google Maps</a>
                        </li>
                        <li>
                            <a href="maps_vector.html">
                                Vector Maps</a>
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

<?php $this->endBody() ?>
</body>
<!-- END BODY -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>

<script src="metronic/assets/global/plugins/respond.min.js"></script>
<script src="metronic/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!--<script src="metronic/assets/global/plugins/jquery.min.js" type="text/javascript"></script>-->
<script src="metronic/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<!--<script src="metronic/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>-->
<script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/scripts/layout.js" type="text/javascript"></script>
<!--<script src="metronic/assets/admin/scripts/demo.js" type="text/javascript"></script>-->
<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
    });
</script>
<!-- END JAVASCRIPTS -->
</html>
<?php $this->endPage() ?>