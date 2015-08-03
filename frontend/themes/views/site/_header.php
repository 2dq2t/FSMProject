<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 27/05/2015
 * Time: 7:25 CH
 */
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
                        <a href="<?php echo $baseUrl ?>"><img
                                src="images/data/logo.png"
                                title="Your Store" alt="Your Store" class="img-responsive"/></a>
                    </div>
                </div>
                <script id="result-template" type="text/x-handlebars-template">
                    <div class="ProductSearch u-cf">
                        <img class="ProductSearch-image" src="{{resize_path}}">

                        <div class="ProductSearch-details">
                            <div class="ProductSearch-ProductName">{{product_name}}</div>
                            <div class="ProductSearch-categoryName">{{category_name}}</div>
                        </div>
                    </div>
                </script>
                <div class="header-right">
                    <div  class="col-sm-5 header-search">
                        <?php \yii\widgets\ActiveForm::begin(['method' => 'get', 'action' => ['site/search']]) ?>
                        <!--                            <input type="hidden" name="mode" value="product_name">-->
                        <div class="typeahead">
                            <div id="search" class="u-posRelative input-group">
                                <input class="typeahead-hint " type="text" tabindex="-1" readonly>
                                <input class="typeahead-input input-lg" id="search-input" type="text" name="q"
                                       placeholder="<?= Yii::t('app', 'SearchLabel') ?>">
                                <span id="search-form" class="input-group-btn">
                                     <button type="button" class="btn btn-default btn-lg"></button>
                                 </span>
                                <img class="typeahead-spinner" src="images/spinner.gif">

                            </div>
                            <div class="typeahead-menu"></div>
                        </div>
                        <button class="u-hidden" type="submit">Search</button>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    </div>
                    <div class="col-sm-3 header-cart">
                        <?php require('getCartInfo.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="nav-container" role="navigation">
    <div class="nav-inner">
        <!-- ======= Menu Code START ========= -->
        <div class="container">

            <div id="menu" class="main-menu" style="display: none;">

                <div class="nav-responsive"><span><?= Yii::t('app', 'CategoryLabel') ?></span>

                    <div class="expandable"></div>
                </div>
                <ul id="navbar" class="main-navigation">
                    <?php $result = array();
                    $categories = Yii::$app->Category->getCategory();
                    foreach ($categories as $item) {
                        $cat_name = $item['categoryname'];
                        $product_name = $item['productname'];
                        $result[$cat_name][] = $product_name;
                    }
                    ?>
                    <?php foreach (array_keys($result) as $category): ?>
                        <li>
                            <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/get-product-category&category=" . $category ?>"><?= ucfirst($category) ?></a>

                            <ul>
                                <?php foreach ($result[$category] as $key) { ?>
                                    <li>
                                        <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $key ?>"><?= ucfirst($key) ?></a>
                                    </li>
                                <?php }; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="menu2" class="main-menu" style="display: none;">

                <div class="nav-responsive"><span><?= Yii::t('app', 'CategoryLabel') ?></span>

                    <div class="expandable"></div>
                </div>
                <ul id="navbar" class="main-navigation">
                        <li>
                            <a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home icon-3x"></i> Trang chủ</a>
                        </li>
                    <li>
                        <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product="?>">Rau theo mùa</a>
                    </li>
                </ul>
            </div>
            <nav id="top">
                <div class="container">
                    <div id="top-links" class="nav">
                        <ul class="list-inline">
                            <?php if (Yii::$app->user->isGuest): ?>
                                <li>
                                    <a href="index.php?r=site/register">Đăng Ký</a>
                                </li>
                                <li>
                                    <a href="index.php?r=site/login">Đăng Nhập</a>
                                </li>
                            <?php else: ?>
                                <li class="dropdown myaccount"><a
                                        href="#"
                                        title="My Account" class="dropdown-toggle"
                                        data-toggle="dropdown"><span><?= Yii::$app->user->identity->username ?></span>
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right myaccount-menu">
                                        <li>
                                            <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id; ?>"><?= Yii::t('app', 'MyAccountLabel') ?></a>
                                        </li>
                                        <li>
                                            <a href="#"><?= Yii::t('app', 'MyOrderLabel') ?></a>
                                        </li>
                                        <li>
                                            <a href="<?= $baseUrl . "/index.php?r=site/logout" ?>"
                                               data-method="post"><?= Yii::t('app', 'LogoutLabel') ?></a>
                                        </li>
                                    </ul>
                                </li>

                                <li><a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=site/get-wish-list"; ?>"
                                       id="wishlist-total"
                                       title="Danh mục yêu thích (0)"> <span><?= Yii::t('app', 'WishListLabel') ?><?php if (!Yii::$app->user->isGuest) {
                                                $number_product = Yii::$app->HeaderInfo->getNumberProductWishList(Yii::$app->user->identity->getId());
                                                echo " (" . $number_product . ")";
                                            } ?></span></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- ======= Menu Code END ========= -->
        </div>
    </div>
</nav>