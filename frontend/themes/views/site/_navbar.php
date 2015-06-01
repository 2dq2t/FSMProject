<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 27/05/2015
 * Time: 7:25 CH
 */
$baseUrl= Yii::$app->request->baseUrl;
?>
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
                    <?php $result = array();
                    foreach ($modelCategory as $item) {
                        $cat_name = $item['categoryname'];
                        $product_name = $item['productname'];
                        $result[$cat_name][] = $product_name;
                    }
                    ?>
                    <?php foreach (array_keys($result) as $categories): ?>
                    <li>
                        <a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$categories?>"><?= $categories ?></a>

                        <ul>
                            <?php foreach ($result[$categories] as $key) { ?>
                            <li>
                                <a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/view-detail&product=".$key?>"><?= $key ?></a>
                            </li>
                            <?php }; ?>
                        </ul>
                    </li>
                    <?php endforeach; ?>
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