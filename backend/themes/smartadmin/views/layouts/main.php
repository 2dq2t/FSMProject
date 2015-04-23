<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
//use kartik\helpers\Html;
use kartik\form\ActiveField;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <?php $this->head() ?>
        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="smartadmin/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="smartadmin/css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
        <link rel="stylesheet" type="text/css" media="screen" href="smartadmin/css/smartadmin-production-plugins.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="smartadmin/css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="smartadmin/css/smartadmin-skins.min.css">

        <!-- SmartAdmin RTL Support -->
        <link rel="stylesheet" type="text/css" media="screen" href="smartadmin/css/smartadmin-rtl.min.css">

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="smartadmin/css/your_style.css"> -->

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="smartadmin/img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="smartadmin/img/favicon/favicon.ico" type="image/x-icon">

        <!-- #GOOGLE FONT -->
        <!--		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">-->

        <!-- #APP SCREEN / ICONS -->
        <!-- Specifying a Webpage Icon for Web Clip
             Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="smartadmin/img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="smartadmin/img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="smartadmin/img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="smartadmin/img/splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="smartadmin/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="smartadmin/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="smartadmin/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

    </head>

    <!--

    TABLE OF CONTENTS.

    Use search to find needed section.

    ===================================================================

    |  01. #CSS Links                |  all CSS links and file paths  |
    |  02. #FAVICONS                 |  Favicon links and file paths  |
    |  03. #GOOGLE FONT              |  Google font link              |
    |  04. #APP SCREEN / ICONS       |  app icons, screen backdrops   |
    |  05. #BODY                     |  body tag                      |
    |  06. #HEADER                   |  header tag                    |
    |  07. #PROJECTS                 |  project lists                 |
    |  08. #TOGGLE LAYOUT BUTTONS    |  layout buttons and actions    |
    |  09. #MOBILE                   |  mobile view dropdown          |
    |  10. #SEARCH                   |  search field                  |
    |  11. #NAVIGATION               |  left panel & navigation       |
    |  12. #MAIN PANEL               |  main panel                    |
    |  13. #MAIN CONTENT             |  content holder                |
    |  14. #PAGE FOOTER              |  page footer                   |
    |  15. #SHORTCUT AREA            |  dropdown shortcuts area       |
    |  16. #PLUGINS                  |  all scripts and plugins       |

    ===================================================================

    -->

    <!-- #BODY -->
    <!-- Possible Classes

        * 'smart-style-{SKIN#}'
        * 'smart-rtl'         - Switch theme mode to RTL
        * 'menu-on-top'       - Switch to top navigation (no DOM change required)
        * 'no-menu'			  - Hides the menu completely
        * 'hidden-menu'       - Hides the main menu but still accessable by hovering over left edge
        * 'fixed-header'      - Fixes the header
        * 'fixed-navigation'  - Fixes the main menu
        * 'fixed-ribbon'      - Fixes breadcrumb
        * 'fixed-page-footer' - Fixes footer
        * 'container'         - boxed layout mode (non-responsive: will not work with fixed-navigation & fixed-ribbon)
    -->
    <body class="fixed-page-footer">
    <?php $this->beginBody() ?>
    <!-- #HEADER -->
    <header id="header">
        <div id="logo-group">

            <!-- PLACE YOUR LOGO HERE -->
            <span id="logo"> <img src="smartadmin/img/logo.png" alt="SmartAdmin"> </span>
            <!-- END LOGO PLACEHOLDER -->
        </div>

        <!-- #TOGGLE LAYOUT BUTTONS -->
        <!-- pulled right: nav area -->
        <div class="pull-right">

            <!-- collapse menu button -->
            <div id="hide-menu" class="btn-header pull-right">
                <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
            </div>
            <!-- end collapse menu -->

            <!-- #MOBILE -->
            <!-- Top menu profile link : this shows only when top menu is active -->
            <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
                <li class="">
                    <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">
                        <img src="smartadmin/img/avatars/sunny.png" alt="John Doe" class="online" />
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#ajax/profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- logout button -->
            <div id="logout" class="btn-header transparent pull-right">
                <span> <a href="#" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
            </div>
            <!-- end logout button -->

            <!-- fullscreen button -->
            <div id="fullscreen" class="btn-header transparent pull-right">
                <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
            </div>
            <!-- end fullscreen button -->

        </div>
        <!-- end pulled right: nav area -->

    </header>
    <!-- END HEADER -->

    <!-- #NAVIGATION -->
    <!-- Left panel : Navigation area -->
    <!-- Note: This width of the aside area can be adjusted through LESS/SASS variables -->
    <aside id="left-panel">

        <!-- User info -->
        <div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
					
					<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        <img src="smartadmin/img/avatars/sunny.png" alt="me" class="online" />
						<span>
							john.doe 
						</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
					
				</span>
        </div>
        <!-- end user info -->

        <!-- NAVIGATION : This navigation is also responsive

        To make this navigation dynamic please make sure to link the node
        (the reference to the nav > ul) after page load. Or the navigation
        will not initialize.
        -->
        <nav>
            <!--
            NOTE: Notice the gaps after each icon usage <i></i>..
            Please note that these links work a bit different than
            traditional href="" links. See documentation for details.
            -->

            <ul>
                <li class="">
                    <a href="ajax/dashboard.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
                </li>
                <li>
                    <a href="ajax/inbox.html"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span><span class="badge pull-right inbox-badge">14</span></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-lg fa-fw fa-shopping-cart"></i> <span class="menu-item-parent">Shop</span></a>
                    <ul>
                        <li><?= HtmL::a('<i class="fa fa-lg fa-fw fa-tags"></i> Categories', ['category/index']) ?></li>
                        <li><?= HtmL::a('<i class="fa fa-lg fa-fw fa-list"></i> Products', ['product/index']) ?></li>
                        <li><?= HtmL::a('<i class="fa fa-lg fa-fw fa-list-alt"></i> Orders', ['order/index']) ?></li>
                        <li><?= HtmL::a('<i class="fa fa-lg fa-fw fa-star"></i> Rating', ['rating/index']) ?></li>
                        <li><?= HtmL::a('<i class="fa fa-lg fa-fw fa-cubes"></i> Attribute', ['attribute/index']) ?></li>
                    </ul>
                </li>
                <li>
                    <?= Html::a('<i class="fa fa-lg fa-fw fa-users"></i><span class="menu-item-parent">Users</span>', ['user/index']) ?>
                </li>
                <li>
                    <a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Graphs</span></a>
                    <ul>
                        <li>
                            <a href="ajax/flot.html">Flot Chart</a>
                        </li>
                        <li>
                            <a href="ajax/morris.html">Morris Charts</a>
                        </li>
                        <li>
                            <a href="ajax/inline-charts.html">Inline Charts</a>
                        </li>
                        <li>
                            <a href="ajax/dygraphs.html">Dygraphs</a>
                        </li>
                        <li>
                            <a href="ajax/chartjs.html">Chart.js <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-lg fa-fw fa-table"></i> <span class="menu-item-parent">Tables</span></a>
                    <ul>
                        <li>
                            <a href="ajax/table.html">Normal Tables</a>
                        </li>
                        <li>
                            <a href="ajax/datatables.html">Data Tables <span class="badge inbox-badge bg-color-greenLight">v1.10</span></a>
                        </li>
                        <li>
                            <a href="ajax/jqgrid.html">Jquery Grid</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Forms</span></a>
                    <ul>
                        <li>
                            <a href="ajax/form-elements.html">Smart Form Elements</a>
                        </li>
                        <li>
                            <a href="ajax/form-templates.html">Smart Form Layouts</a>
                        </li>
                        <li>
                            <a href="ajax/validation.html">Smart Form Validation</a>
                        </li>
                        <li>
                            <a href="ajax/bootstrap-forms.html">Bootstrap Form Elements</a>
                        </li>
                        <li>
                            <a href="ajax/bootstrap-validator.html">Bootstrap Form Validation</a>
                        </li>
                        <li>
                            <a href="ajax/plugins.html">Form Plugins</a>
                        </li>
                        <li>
                            <a href="ajax/wizard.html">Wizards</a>
                        </li>
                        <li>
                            <a href="ajax/other-editors.html">Bootstrap Editors</a>
                        </li>
                        <li>
                            <a href="ajax/dropzone.html">Dropzone</a>
                        </li>
                        <li>
                            <a href="ajax/image-editor.html">Image Cropping <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-lg fa-fw fa-desktop"></i> <span class="menu-item-parent">UI Elements</span></a>
                    <ul>
                        <li>
                            <a href="ajax/general-elements.html">General Elements</a>
                        </li>
                        <li>
                            <a href="ajax/buttons.html">Buttons</a>
                        </li>
                        <li>
                            <a href="#">Icons</a>
                            <ul>
                                <li>
                                    <a href="ajax/fa.html"><i class="fa fa-plane"></i> Font Awesome</a>
                                </li>
                                <li>
                                    <a href="ajax/glyph.html"><i class="glyphicon glyphicon-plane"></i> Glyph Icons</a>
                                </li>
                                <li>
                                    <a href="ajax/flags.html"><i class="fa fa-flag"></i> Flags</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="ajax/grid.html">Grid</a>
                        </li>
                        <li>
                            <a href="ajax/treeview.html">Tree View</a>
                        </li>
                        <li>
                            <a href="ajax/nestable-list.html">Nestable Lists</a>
                        </li>
                        <li>
                            <a href="ajax/jqui.html">JQuery UI</a>
                        </li>
                        <li>
                            <a href="ajax/typography.html">Typography</a>
                        </li>
                        <li>
                            <a href="#">Six Level Menu</a>
                            <ul>
                                <li>
                                    <a href="#"><i class="fa fa-fw fa-folder-open"></i> Item #2</a>
                                    <ul>
                                        <li>
                                            <a href="#"><i class="fa fa-fw fa-folder-open"></i> Sub #2.1 </a>
                                            <ul>
                                                <li>
                                                    <a href="#"><i class="fa fa-fw fa-file-text"></i> Item #2.1.1</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-fw fa-plus"></i> Expand</a>
                                                    <ul>
                                                        <li>
                                                            <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                        </li>
                                                        <li>
                                                            <a href="#"><i class="fa fa-fw fa-trash-o"></i> Delete</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-fw fa-folder-open"></i> Item #3</a>

                                    <ul>
                                        <li>
                                            <a href="#"><i class="fa fa-fw fa-folder-open"></i> 3ed Level </a>
                                            <ul>
                                                <li>
                                                    <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#" class="inactive"><i class="fa fa-fw fa-folder-open"></i> Item #4 (disabled)</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="ajax/calendar.html"><i class="fa fa-lg fa-fw fa-calendar"><em>3</em></i> <span class="menu-item-parent">Calendar</span></a>
                </li>
                <li>
                    <a href="ajax/widgets.html"><i class="fa fa-lg fa-fw fa-list-alt"></i> <span class="menu-item-parent">Widgets</span></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-lg fa-fw fa-puzzle-piece"></i> <span class="menu-item-parent">App Views</span></a>
                    <ul>
                        <li>
                            <a href="ajax/projects.html"><i class="fa fa-file-text-o"></i> Projects</a>
                        </li>
                        <li>
                            <a href="ajax/blog.html"><i class="fa fa-paragraph"></i> Blog</a>
                        </li>
                        <li>
                            <a href="ajax/gallery.html"><i class="fa fa-picture-o"></i> Gallery</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-comments"></i> Forum Layout</a>
                            <ul>
                                <li><a href="ajax/forum.html">General View</a></li>
                                <li><a href="ajax/forum-topic.html">Topic View</a></li>
                                <li><a href="ajax/forum-post.html">Post View</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="ajax/profile.html"><i class="fa fa-group"></i> Profile</a>
                        </li>
                        <li>
                            <a href="ajax/timeline.html"><i class="fa fa-clock-o"></i> Timeline</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="ajax/gmap-xml.html"><i class="fa fa-lg fa-fw fa-map-marker"></i> <span class="menu-item-parent">GMap Skins</span><span class="badge bg-color-greenLight pull-right inbox-badge">9</span></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-lg fa-fw fa-windows"></i> <span class="menu-item-parent">Miscellaneous</span></a>
                    <ul>
                        <li>
                            <a href="http://bootstraphunter.com/smartadmin-landing/" target="_blank">Landing Page <i class="fa fa-external-link"></i></a>
                        </li>
                        <li>
                            <a href="ajax/pricing-table.html">Pricing Tables</a>
                        </li>
                        <li>
                            <a href="ajax/invoice.html">Invoice</a>
                        </li>
                        <li>
                            <a href="login.html" target="_top">Login</a>
                        </li>
                        <li>
                            <a href="register.html" target="_top">Register</a>
                        </li>
                        <li>
                            <a href="lock.html" target="_top">Locked Screen</a>
                        </li>
                        <li>
                            <a href="ajax/error404.html">Error 404</a>
                        </li>
                        <li>
                            <a href="ajax/error500.html">Error 500</a>
                        </li>
                        <li>
                            <a href="ajax/blank_.html">Blank Page</a>
                        </li>
                        <li>
                            <a href="ajax/email-template.html">Email Template</a>
                        </li>
                        <li>
                            <a href="ajax/search.html">Search Page</a>
                        </li>
                        <li>
                            <a href="ajax/ckeditor.html">CK Editor</a>
                        </li>
                    </ul>
                </li>
                <li class="top-menu-invisible">
                    <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">SmartAdmin Intel</span></a>
                    <ul>
                        <li>
                            <a href="ajax/difver.html"><i class="fa fa-stack-overflow"></i> Different Versions</a>
                        </li>
                        <li>
                            <a href="ajax/applayout.html"><i class="fa fa-cube"></i> App Settings</a>
                        </li>
                        <li>
                            <a href="http://192.241.236.31/smartadmin/BUGTRACK/track_/documentation/index.html" target="_blank"><i class="fa fa-book"></i> Documentation</a>
                        </li>
                        <li>
                            <a href="http://192.241.236.31/smartadmin/BUGTRACK/track_/" target="_blank"><i class="fa fa-bug"></i> Bug Tracker</a>
                        </li>
                        <li>
                            <a href="http://myorange.ca/supportforum/" target="_blank"><i class="fa fa-wechat"></i> SmartAdmin Support</a>
                        </li>
                    </ul>
                </li>
                <li class="chat-users top-menu-invisible">
                    <a href="#"><i class="fa fa-lg fa-fw fa-comment-o"><em class="bg-color-pink flash animated">!</em></i> <span class="menu-item-parent">Smart Chat API <sup>beta</sup></span></a>
                    <ul>
                        <li>
                            <!-- DISPLAY USERS -->
                            <div class="display-users">

                                <input class="form-control chat-user-filter" placeholder="Filter" type="text">

                                <a href="#" class="usr"
                                   data-chat-id="cha1"
                                   data-chat-fname="Sadi"
                                   data-chat-lname="Orlaf"
                                   data-chat-status="busy"
                                   data-chat-alertmsg="Sadi Orlaf is in a meeting. Please do not disturb!"
                                   data-chat-alertshow="true"
                                   data-rel="popover-hover"
                                   data-placement="right"
                                   data-html="true"
                                   data-content="
											<div class='usr-card'>
												<img src='img/avatars/5.png' alt='Sadi Orlaf'>
												<div class='usr-card-content'>
													<h3>Sadi Orlaf</h3>
													<p>Marketing Executive</p>
												</div>
											</div>
										">
                                    <i></i>Sadi Orlaf
                                </a>

                                <a href="#" class="usr"
                                   data-chat-id="cha2"
                                   data-chat-fname="Jessica"
                                   data-chat-lname="Dolof"
                                   data-chat-status="online"
                                   data-chat-alertmsg=""
                                   data-chat-alertshow="false"
                                   data-rel="popover-hover"
                                   data-placement="right"
                                   data-html="true"
                                   data-content="
											<div class='usr-card'>
												<img src='img/avatars/1.png' alt='Jessica Dolof'>
												<div class='usr-card-content'>
													<h3>Jessica Dolof</h3>
													<p>Sales Administrator</p>
												</div>
											</div>
										">
                                    <i></i>Jessica Dolof
                                </a>

                                <a href="#" class="usr"
                                   data-chat-id="cha3"
                                   data-chat-fname="Zekarburg"
                                   data-chat-lname="Almandalie"
                                   data-chat-status="online"
                                   data-rel="popover-hover"
                                   data-placement="right"
                                   data-html="true"
                                   data-content="
											<div class='usr-card'>
												<img src='smartadmin/img/avatars/3.png' alt='Zekarburg Almandalie'>
												<div class='usr-card-content'>
													<h3>Zekarburg Almandalie</h3>
													<p>Sales Admin</p>
												</div>
											</div>
										">
                                    <i></i>Zekarburg Almandalie
                                </a>

                                <a href="#" class="usr"
                                   data-chat-id="cha4"
                                   data-chat-fname="Barley"
                                   data-chat-lname="Krazurkth"
                                   data-chat-status="away"
                                   data-rel="popover-hover"
                                   data-placement="right"
                                   data-html="true"
                                   data-content="
											<div class='usr-card'>
												<img src='img/avatars/4.png' alt='Barley Krazurkth'>
												<div class='usr-card-content'>
													<h3>Barley Krazurkth</h3>
													<p>Sales Director</p>
												</div>
											</div>
										">
                                    <i></i>Barley Krazurkth
                                </a>

                                <a href="#" class="usr offline"
                                   data-chat-id="cha5"
                                   data-chat-fname="Farhana"
                                   data-chat-lname="Amrin"
                                   data-chat-status="incognito"
                                   data-rel="popover-hover"
                                   data-placement="right"
                                   data-html="true"
                                   data-content="
											<div class='usr-card'>
												<img src='img/avatars/female.png' alt='Farhana Amrin'>
												<div class='usr-card-content'>
													<h3>Farhana Amrin</h3>
													<p>Support Admin <small><i class='fa fa-music'></i> Playing Beethoven Classics</small></p>
												</div>
											</div>
										">
                                    <i></i>Farhana Amrin (offline)
                                </a>

                                <a href="#" class="usr offline"
                                   data-chat-id="cha6"
                                   data-chat-fname="Lezley"
                                   data-chat-lname="Jacob"
                                   data-chat-status="incognito"
                                   data-rel="popover-hover"
                                   data-placement="right"
                                   data-html="true"
                                   data-content="
											<div class='usr-card'>
												<img src='img/avatars/male.png' alt='Lezley Jacob'>
												<div class='usr-card-content'>
													<h3>Lezley Jacob</h3>
													<p>Sales Director</p>
												</div>
											</div>
										">
                                    <i></i>Lezley Jacob (offline)
                                </a>

                                <a href="ajax/chat.html" class="btn btn-xs btn-default btn-block sa-chat-learnmore-btn">About the API</a>

                            </div>
                            <!-- END DISPLAY USERS -->
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

    </aside>
    <!-- END NAVIGATION -->

    <!-- #MAIN PANEL -->
    <div id="main" role="main">

        <!-- RIBBON -->
        <div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true" data-reset-msg="Would you like to RESET all your saved widgets and clear LocalStorage?"><i class="fa fa-refresh"></i></span> 
				</span>

            <!-- breadcrumb -->
            <!--<ol class="breadcrumb">-->
            <!-- This is auto generated -->
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!--</ol>-->
            <!-- end breadcrumb -->

            <!-- You can also add more buttons to the
            ribbon for further usability

            Example below:

            <span class="ribbon-button-alignment pull-right" style="margin-right:25px">
                <a href="#" id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa fa-grid"></i> Change Grid</a>
                <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa fa-plus"></i> Add</span>
                <button id="search" class="btn btn-ribbon" data-title="search"><i class="fa fa-search"></i> <span class="hidden-mobile">Search</span></button>
            </span> -->

        </div>
        <!-- END RIBBON -->

        <!-- #MAIN CONTENT -->
        <div id="content">
            <?= $content ?>
        </div>

        <!-- END #MAIN CONTENT -->

    </div>
    <!-- END #MAIN PANEL -->

    <!-- #PAGE FOOTER -->
    <div class="page-footer">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <span class="txt-color-white">SmartAdmin 1.5 <span class="hidden-xs"> - Web Application Framework</span> © 2014-<?= date("Y") ?></span>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- END FOOTER -->

    <!-- #SHORTCUT AREA : With large tiles (activated via clicking user name tag)
         Note: These tiles are completely responsive, you can add as many as you like -->
    <div id="shortcut">
        <ul>
            <li>
                <a href="#ajax/inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
            </li>
            <li>
                <a href="#ajax/calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
            </li>
            <li>
                <a href="#ajax/gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
            </li>
            <li>
                <a href="#ajax/invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
            </li>
            <li>
                <a href="#ajax/gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
            </li>
            <li>
                <a href="#ajax/profile.html" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
            </li>
        </ul>
    </div>
    <!-- END SHORTCUT AREA -->

    <!--================================================== -->

    <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
    <script data-pace-options='{ "restartOnRequestAfter": true }' src="smartadmin/js/plugin/pace/pace.min.js"></script>-->
    <!-- #PLUGINS -->
    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
    <!--<script src="smartadmin/js/libs/jquery-2.1.1.min.js"></script>
    <script>
        if (!window.jQuery) {
            document.write('<script src="smartadmin/js/libs/jquery-2.1.1.min.js"><\/script>');
        }
    </script>-->


    <!-- BOOTSTRAP JS -->
    <!--<script src="smartadmin/js/bootstrap/bootstrap.min.js"></script>-->


    <!-- JARVIS WIDGETS -->
    <!--<script src="smartadmin/js/smartwidgets/jarvis.widget.min.js"></script>-->

    <!-- EASY PIE CHARTS -->
    <!--<script src="smartadmin/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>-->

    <!-- SPARKLINES -->
    <!--<script src="smartadmin/js/plugin/sparkline/jquery.sparkline.min.js"></script>-->

    <!-- JQUERY VALIDATE -->
    <!--<script src="smartadmin/js/plugin/jquery-validate/jquery.validate.min.js"></script>-->

    <!-- JQUERY MASKED INPUT -->
    <!--<script src="smartadmin/js/plugin/masked-input/jquery.maskedinput.min.js"></script>-->

    <!-- JQUERY SELECT2 INPUT -->
    <!--<script src="smartadmin/js/plugin/select2/select2.min.js"></script>-->

    <!-- JQUERY UI + Bootstrap Slider -->
    <!--<script src="smartadmin/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>-->


    <?php $this->endBody() ?>
    </body>


    <script src="smartadmin/js/libs/jquery-ui-1.10.3.min.js"></script>
    <script>
        if (!window.jQuery.ui) {
            document.write('<script src="smartadmin/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
        }
    </script>

    <!-- JARVIS WIDGETS -->
    <script src="smartadmin/js/smartwidgets/jarvis.widget.min.js"></script>

    <!-- SPARKLINES -->
    <script src="smartadmin/js/plugin/sparkline/jquery.sparkline.min.js"></script>

    <!-- JQUERY VALIDATE -->
    <script src="smartadmin/js/plugin/jquery-validate/jquery.validate.min.js"></script>

    <!-- JQUERY MASKED INPUT -->
    <script src="smartadmin/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

    <!-- JQUERY SELECT2 INPUT -->
    <script src="smartadmin/js/plugin/select2/select2.min.js"></script>

    <!-- JQUERY UI + Bootstrap Slider -->
    <script src="smartadmin/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

    <!-- browser msie issue fix -->
    <script src="smartadmin/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

    <!-- FastClick: For mobile devices: you can disable this in app.js -->
    <script src="smartadmin/js/plugin/fastclick/fastclick.min.js"></script>

    <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
    <script src="smartadmin/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>


    <!-- IMPORTANT: APP CONFIG -->
    <script src="smartadmin/js/app.config.js"></script>
    <!-- CUSTOM NOTIFICATION -->
    <script src="smartadmin/js/notification/SmartNotification.min.js"></script>

    <!--[if IE 8]>
    <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
    <![endif]-->

    <!-- MAIN APP JS FILE -->
    <script src="smartadmin/js/app.min.js"></script>
    </html>
<?php $this->endPage() ?>