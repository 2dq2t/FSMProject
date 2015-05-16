<?php
use kartik\alert\Alert;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php
include '_search.php';
?>
<div id="top-banner-and-menu">
    <div class="container">
        <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
            <?php
            echo Alert::widget([
                'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
                'title' => (!empty($message['title'])) ? Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
                'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                'body' => (!empty($message['message'])) ? Html::encode($message['message']) : Yii::t('app', 'Message Not Set!'),
                'showSeparator' => true,
                'delay' => 3000
            ]);
            ?>
        <?php endforeach; ?>
        <div class="col-xs-12 col-sm-4 col-md-3 sidemenu-holder">
            <!-- ================================== TOP NAVIGATION ================================== -->
            <div class="side-menu animate-dropdown">
                <div class="head"><i class="fa fa-list"></i> Danh mục</div>
                <nav class="yamm megamenu-horizontal" role="navigation">
                    <ul class="nav">
                        <?php $result = array();
                        foreach ($modelCategory as $item) {
                            $cat_name = $item['categoryname'];
                            $product_name = $item['productname'];
                            $result[$cat_name][] = $product_name;
                        }
                        ?>
                        <?php foreach (array_keys($result) as $categories): ?>
                            <li class="dropdown menu-item">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $categories ?></a>
                                <ul class="dropdown-menu mega-menu">
                                    <li class="yamm-content">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <ul class="list-unstyled">
                                                    <?php foreach ($result[$categories] as $key) { ?>
                                                        <li><a href="/FSMProject/frontend/web/index.php?r=site/view-detail&product=<?php echo $key;?>"><?= $key ?></a></li>
                                                    <?php }; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li><!-- /.menu-item -->
                        <?php endforeach; ?>
                    </ul>
                </nav>
                <!-- /.megamenu-horizontal -->
            </div>
            <!-- /.side-menu -->


            <!-- ================================== TOP NAVIGATION : END ================================== -->
        </div>
        <!-- /.sidemenu-holder -->

        <div class="col-xs-12 col-sm-8 col-md-9 homebanner-holder">
            <!-- ========================================== SECTION – HERO ========================================= -->

            <div id="hero">
                <div id="owl-main" class="owl-carousel owl-inner-nav owl-ui-sm">
                    <?php
                    foreach ($slideShow as $item) {
                        $imageUrl = 'uploads/slideshow/' . $item['id'] . '/' . $item['path'];
                        echo "<div class='item' style='background-image: url(" . $imageUrl . ");'>";
                        echo "<div class='container-fluid'>";
                        echo "<div class='caption vertical-center text-left'>";
                        echo "<div class='big-text fadeInDown-1'></br>";
                        echo $item['title'];
                        echo "</div>";

                        echo "<div class='excerpt fadeInDown-2'>";
                        echo $item['description'];
                        echo "</div>";
                        echo "</div>"; //<!-- /.caption -->
                        echo "</div>"; //<!-- /.container-fluid -->
                        echo "</div>"; //<!-- /.item -->
                    }
                    ?>
                </div>
                <!-- /.owl-carousel -->
            </div>
            <!-- ========================================= SECTION – HERO : END ========================================= -->
        </div>
        <!-- /.homebanner-holder -->
    </div>
    <!-- /.container -->
</div><!-- /#top-banner-and-menu -->

<!-- ========================================= HOME BANNERS ========================================= -->
<section id="banner-holder" class="wow fadeInUp">
    <div class="container">
        <div class="col-xs-12 col-lg-6 no-margin banner">
            <a href="category-grid-2.html">
                <div class="banner-text theblue">
                    <h1>New Life</h1>
                    <span class="tagline">Introducing New Category</span>
                </div>
                <img class="banner-image" alt="" src="mediacenter/assets/images/blank.gif"
                     data-echo="mediacenter/assets/images/banners/banner-narrow-01.jpg"/>
            </a>
        </div>
        <div class="col-xs-12 col-lg-6 no-margin text-right banner">
            <a href="category-grid-2.html">
                <div class="banner-text right">
                    <h1>Time &amp; Style</h1>
                    <span class="tagline">Checkout new arrivals</span>
                </div>
                <img class="banner-image" alt="" src="mediacenter/assets/images/blank.gif"
                     data-echo="mediacenter/assets/images/banners/banner-narrow-02.jpg"/>
            </a>
        </div>
    </div>
    <!-- /.container -->
</section><!-- /#banner-holder -->
<!-- ========================================= HOME BANNERS : END ========================================= -->
<div id="products-tab" class="wow fadeInUp">
    <div class="container">
        <div class="tab-holder">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#new-product" data-toggle="tab">Sản phẩm mới</a></li>
                <li><a href="#new-arrivals" data-toggle="tab">new arrivals</a></li>
                <li><a href="#top-sales" data-toggle="tab">top sales</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="new-product">
                    <div class="product-grid-holder">
                        <div class="col-sm-4 col-md-3  no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon red"><span>sale</span></div>
                                <div class="image">
                                    <a href=""> <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-01.jpg"/></a>
                                </div>
                                <div class="body">
                                    <div class="label-discount green">-50% sale</div>
                                    <div class="title">
                                        <a href="single-product.html">VAIO Fit Laptop - Windows 8 SVF14322CXW</a>
                                    </div>
                                    <div class="brand">sony</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>

                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon blue"><span>new!</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-02.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">White lumia 9001</a>
                                    </div>
                                    <div class="brand">nokia</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">

                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-03.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">POV Action Cam</a>
                                    </div>
                                    <div class="brand">sony</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon red"><span>sale</span></div>
                                <div class="ribbon green"><span>bestseller</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-04.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">Netbook Acer TravelMate
                                            B113-E-10072</a>
                                    </div>
                                    <div class="brand">acer</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loadmore-holder text-center">
                        <a class="btn-loadmore" href="#">
                            <i class="fa fa-plus"></i>
                            load more products</a>
                    </div>

                </div>
                <div class="tab-pane" id="new-arrivals">
                    <div class="product-grid-holder">

                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon blue"><span>new!</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-02.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">White lumia 9001</a>
                                    </div>
                                    <div class="brand">nokia</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon red"><span>sale</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-01.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount green">-50% sale</div>
                                    <div class="title">
                                        <a href="single-product.html">VAIO Fit Laptop - Windows 8 SVF14322CXW</a>
                                    </div>
                                    <div class="brand">sony</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>

                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon red"><span>sale</span></div>
                                <div class="ribbon green"><span>bestseller</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-04.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">Netbook Acer TravelMate
                                            B113-E-10072</a>
                                    </div>
                                    <div class="brand">acer</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">

                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-03.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">POV Action Cam</a>
                                    </div>
                                    <div class="brand">sony</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="loadmore-holder text-center">
                        <a class="btn-loadmore" href="#">
                            <i class="fa fa-plus"></i>
                            load more products</a>
                    </div>

                </div>

                <div class="tab-pane" id="top-sales">
                    <div class="product-grid-holder">

                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon red"><span>sale</span></div>
                                <div class="ribbon green"><span>bestseller</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-04.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">Netbook Acer TravelMate
                                            B113-E-10072</a>
                                    </div>
                                    <div class="brand">acer</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">

                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-03.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">POV Action Cam</a>
                                    </div>
                                    <div class="brand">sony</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon blue"><span>new!</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-02.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount clear"></div>
                                    <div class="title">
                                        <a href="single-product.html">White lumia 9001</a>
                                    </div>
                                    <div class="brand">nokia</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>
                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-3 no-margin product-item-holder hover">
                            <div class="product-item">
                                <div class="ribbon red"><span>sale</span></div>
                                <div class="image">
                                    <img alt="" src="mediacenter/assets/images/blank.gif"
                                         data-echo="mediacenter/assets/images/products/product-01.jpg"/>
                                </div>
                                <div class="body">
                                    <div class="label-discount green">-50% sale</div>
                                    <div class="title">
                                        <a href="single-product.html">VAIO Fit Laptop - Windows 8 SVF14322CXW</a>
                                    </div>
                                    <div class="brand">sony</div>
                                </div>
                                <div class="prices">
                                    <div class="price-prev">$1399.00</div>
                                    <div class="price-current pull-right">$1199.00</div>
                                </div>

                                <div class="hover-area">
                                    <div class="add-cart-button">
                                        <a href="single-product.html" class="le-button">add to cart</a>
                                    </div>
                                    <div class="wish-compare">
                                        <a class="btn-add-to-wishlist" href="#">add to wishlist</a>
                                        <a class="btn-add-to-compare" href="#">compare</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="loadmore-holder text-center">
                        <a class="btn-loadmore" href="#">
                            <i class="fa fa-plus"></i>
                            load more products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ========================================= BEST SELLERS ========================================= -->
<section id="bestsellers" class="color-bg wow fadeInUp">
    <div class="container">
        <h1 class="section-title">Sản phẩm theo mùa</h1>

        <div class="product-grid-holder medium">
            <div class="col-xs-12 col-md-7 no-margin">

                <div class="row no-margin">
                    <div class="col-xs-12 col-sm-4 no-margin product-item-holder size-medium hover">
                        <div class="product-item">
                            <div class="image">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-05.jpg"/>
                            </div>
                            <div class="body">
                                <div class="label-discount clear"></div>
                                <div class="title">
                                    <a href="single-product.html">beats studio headphones official one</a>
                                </div>
                                <div class="brand">beats</div>
                            </div>
                            <div class="prices">

                                <div class="price-current text-right">$1199.00</div>
                            </div>
                            <div class="hover-area">
                                <div class="add-cart-button">
                                    <a href="single-product.html" class="le-button">Add to cart</a>
                                </div>
                                <div class="wish-compare">
                                    <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                    <a class="btn-add-to-compare" href="#">Compare</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item-holder -->

                    <div class="col-xs-12 col-sm-4 no-margin product-item-holder size-medium hover">
                        <div class="product-item">
                            <div class="image">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-06.jpg"/>
                            </div>
                            <div class="body">
                                <div class="label-discount clear"></div>
                                <div class="title">
                                    <a href="single-product.html">playstasion 4 with four games and pad</a>
                                </div>
                                <div class="brand">acer</div>
                            </div>
                            <div class="prices">
                                <div class="price-current text-right">$1199.00</div>
                            </div>
                            <div class="hover-area">
                                <div class="add-cart-button">
                                    <a href="single-product.html" class="le-button">Add to cart</a>
                                </div>
                                <div class="wish-compare">
                                    <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                    <a class="btn-add-to-compare" href="#">Compare</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item-holder -->

                    <div class="col-xs-12 col-sm-4 no-margin product-item-holder size-medium hover">
                        <div class="product-item">
                            <div class="image">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-07.jpg"/>
                            </div>
                            <div class="body">
                                <div class="label-discount clear"></div>
                                <div class="title">
                                    <a href="single-product.html">EOS rebel t5i DSLR Camera with 18-55mm IS STM lens</a>
                                </div>
                                <div class="brand">canon</div>
                            </div>
                            <div class="prices">
                                <div class="price-current text-right">$1199.00</div>
                            </div>
                            <div class="hover-area">
                                <div class="add-cart-button">
                                    <a href="single-product.html" class="le-button">Add to cart</a>
                                </div>
                                <div class="wish-compare">
                                    <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                    <a class="btn-add-to-compare" href="#">Compare</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item-holder -->
                </div>
                <!-- /.row -->

                <div class="row no-margin">

                    <div class="col-xs-12 col-sm-4 no-margin product-item-holder size-medium hover">
                        <div class="product-item">
                            <div class="image">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-08.jpg"/>
                            </div>
                            <div class="body">
                                <div class="label-discount clear"></div>
                                <div class="title">
                                    <a href="single-product.html">fitbit zip wireless activity tracker - lime</a>
                                </div>
                                <div class="brand">fitbit zip</div>
                            </div>
                            <div class="prices">
                                <div class="price-current text-right">$1199.00</div>
                            </div>
                            <div class="hover-area">
                                <div class="add-cart-button">
                                    <a href="single-product.html" class="le-button">Add to cart</a>
                                </div>
                                <div class="wish-compare">
                                    <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                    <a class="btn-add-to-compare" href="#">Compare</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item-holder -->

                    <div class="col-xs-12 col-sm-4 no-margin product-item-holder size-medium hover">
                        <div class="product-item">
                            <div class="image">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-09.jpg"/>
                            </div>
                            <div class="body">
                                <div class="label-discount clear"></div>
                                <div class="title">
                                    <a href="single-product.html">PowerShot elph 115 16MP digital camera</a>
                                </div>
                                <div class="brand">canon</div>
                            </div>
                            <div class="prices">
                                <div class="price-current text-right">$1199.00</div>
                            </div>
                            <div class="hover-area">
                                <div class="add-cart-button">
                                    <a href="single-product.html" class="le-button">Add to cart</a>
                                </div>
                                <div class="wish-compare">
                                    <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                    <a class="btn-add-to-compare" href="#">Compare</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item-holder -->

                    <div class="col-xs-12 col-sm-4 no-margin product-item-holder size-medium hover">
                        <div class="product-item">
                            <div class="image">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-10.jpg"/>
                            </div>
                            <div class="body">
                                <div class="label-discount clear"></div>
                                <div class="title">
                                    <a href="single-product.html">netbook acer travelMate b113-E-10072</a>
                                </div>
                                <div class="brand">acer</div>
                            </div>
                            <div class="prices">
                                <div class="price-current text-right">$1199.00</div>
                            </div>
                            <div class="hover-area">
                                <div class="add-cart-button">
                                    <a href="single-product.html" class="le-button">Add to cart</a>
                                </div>
                                <div class="wish-compare">
                                    <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                    <a class="btn-add-to-compare" href="#">Compare</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item-holder -->

                </div>
                <!-- /.row -->
            </div>
            <!-- /.col -->
            <div class="col-xs-12 col-md-5 no-margin">
                <div class="product-item-holder size-big single-product-gallery small-gallery">

                    <div id="best-seller-single-product-slider" class="single-product-slider owl-carousel">
                        <div class="single-product-gallery-item" id="slide1">
                            <a data-rel="prettyphoto" href="images/products/product-gallery-01.jpg">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-gallery-01.jpg"/>
                            </a>
                        </div>
                        <!-- /.single-product-gallery-item -->

                        <div class="single-product-gallery-item" id="slide2">
                            <a data-rel="prettyphoto" href="images/products/product-gallery-01.jpg">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-gallery-01.jpg"/>
                            </a>
                        </div>
                        <!-- /.single-product-gallery-item -->

                        <div class="single-product-gallery-item" id="slide3">
                            <a data-rel="prettyphoto" href="images/products/product-gallery-01.jpg">
                                <img alt="" src="mediacenter/assets/images/blank.gif"
                                     data-echo="mediacenter/assets/images/products/product-gallery-01.jpg"/>
                            </a>
                        </div>
                        <!-- /.single-product-gallery-item -->
                    </div>
                    <!-- /.single-product-slider -->

                    <div class="gallery-thumbs clearfix">
                        <ul>
                            <li><a class="horizontal-thumb active" data-target="#best-seller-single-product-slider"
                                   data-slide="0" href="#slide1"><img alt="" src="mediacenter/assets/images/blank.gif"
                                                                      data-echo="mediacenter/assets/images/products/gallery-thumb-01.jpg"/></a>
                            </li>
                            <li><a class="horizontal-thumb" data-target="#best-seller-single-product-slider"
                                   data-slide="1" href="#slide2"><img alt="" src="mediacenter/assets/images/blank.gif"
                                                                      data-echo="mediacenter/assets/images/products/gallery-thumb-01.jpg"/></a>
                            </li>
                            <li><a class="horizontal-thumb" data-target="#best-seller-single-product-slider"
                                   data-slide="2" href="#slide3"><img alt="" src="mediacenter/assets/images/blank.gif"
                                                                      data-echo="mediacenter/assets/images/products/gallery-thumb-01.jpg"/></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.gallery-thumbs -->

                    <div class="body">
                        <div class="label-discount clear"></div>
                        <div class="title">
                            <a href="single-product.html">CPU intel core i5-4670k 3.4GHz BOX B82-12-122-41</a>
                        </div>
                        <div class="brand">sony</div>
                    </div>
                    <div class="prices text-right">
                        <div class="price-current inline">$1199.00</div>
                        <a href="cart.html" class="le-button big inline">add to cart</a>
                    </div>
                </div>
                <!-- /.product-item-holder -->
            </div>
            <!-- /.col -->

        </div>
        <!-- /.product-grid-holder -->
    </div>
    <!-- /.container -->
</section><!-- /#bestsellers -->
<!-- ========================================= BEST SELLERS : END ========================================= -->
<!-- ========================================= RECENTLY VIEWED ========================================= -->
<section id="recently-reviewd" class="wow fadeInUp">
    <div class="container">
        <div class="carousel-holder hover">

            <div class="title-nav">
                <h2 class="h1">Recently Viewed</h2>

                <div class="nav-holder">
                    <a href="#prev" data-target="#owl-recently-viewed"
                       class="slider-prev btn-prev fa fa-angle-left"></a>
                    <a href="#next" data-target="#owl-recently-viewed"
                       class="slider-next btn-next fa fa-angle-right"></a>
                </div>
            </div>
            <!-- /.title-nav -->

            <div id="owl-recently-viewed" class="owl-carousel product-grid-holder">
                <div class="no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon red"><span>sale</span></div>
                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-11.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">LC-70UD1U 70" class aquos 4K ultra HD</a>
                            </div>
                            <div class="brand">Sharp</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to Cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->

                <div class="no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon blue"><span>new!</span></div>
                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-12.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">cinemizer OLED 3D virtual reality TV Video</a>
                            </div>
                            <div class="brand">zeiss</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">

                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-13.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">s2340T23" full HD multi-Touch Monitor</a>
                            </div>
                            <div class="brand">dell</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon blue"><span>new!</span></div>
                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-14.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">kardon BDS 7772/120 integrated 3D</a>
                            </div>
                            <div class="brand">harman</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon green"><span>bestseller</span></div>
                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-15.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">netbook acer travel B113-E-10072</a>
                            </div>
                            <div class="brand">acer</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">

                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-16.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">iPod touch 5th generation,64GB, blue</a>
                            </div>
                            <div class="brand">apple</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">

                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-13.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">s2340T23" full HD multi-Touch Monitor</a>
                            </div>
                            <div class="brand">dell</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon blue"><span>new!</span></div>
                        <div class="image">
                            <img alt="" src="mediacenter/assets/images/blank.gif"
                                 data-echo="mediacenter/assets/images/products/product-14.jpg"/>
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">kardon BDS 7772/120 integrated 3D</a>
                            </div>
                            <div class="brand">harman</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.product-item -->
                </div>
                <!-- /.product-item-holder -->
            </div>
            <!-- /#recently-carousel -->

        </div>
        <!-- /.carousel-holder -->
    </div>
    <!-- /.container -->
</section><!-- /#recently-reviewd -->
<!-- ========================================= RECENTLY VIEWED : END ========================================= -->
<footer id="footer" class="color-bg">

    <div class="container">
        <div class="row no-margin widgets-row">
            <div class="col-xs-12  col-sm-4 no-margin-left">
                <!-- ============================================================= FEATURED PRODUCTS ============================================================= -->
                <div class="widget">
                    <h2>Featured products</h2>

                    <div class="body">
                        <ul>
                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">Netbook Acer Travel B113-E-10072</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-01.jpg"/>
                                        </a>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">PowerShot Elph 115 16MP Digital Camera</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-02.jpg"/>
                                        </a>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">PowerShot Elph 115 16MP Digital Camera</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-03.jpg"/>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /.body -->
                </div>
                <!-- /.widget -->
                <!-- ============================================================= FEATURED PRODUCTS : END ============================================================= -->
            </div>
            <!-- /.col -->

            <div class="col-xs-12 col-sm-4 ">
                <!-- ============================================================= ON SALE PRODUCTS ============================================================= -->
                <div class="widget">
                    <h2>On-Sale Products</h2>

                    <div class="body">
                        <ul>
                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">HP Scanner 2910P</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-04.jpg"/>
                                        </a>
                                    </div>
                                </div>

                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">Galaxy Tab 3 GT-P5210 16GB, Wi-Fi, 10.1in -
                                            White</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-05.jpg"/>
                                        </a>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">PowerShot Elph 115 16MP Digital Camera</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-06.jpg"/>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /.body -->
                </div>
                <!-- /.widget -->
                <!-- ============================================================= ON SALE PRODUCTS : END ============================================================= -->
            </div>
            <!-- /.col -->

            <div class="col-xs-12 col-sm-4 ">
                <!-- ============================================================= TOP RATED PRODUCTS ============================================================= -->
                <div class="widget">
                    <h2>Top Rated Products</h2>

                    <div class="body">
                        <ul>
                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">Galaxy Tab GT-P5210, 10" 16GB Wi-Fi</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-07.jpg"/>
                                        </a>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">PowerShot Elph 115 16MP Digital Camera</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-08.jpg"/>
                                        </a>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-9 no-margin">
                                        <a href="single-product.html">Surface RT 64GB, Wi-Fi, 10.6in - Dark Titanium</a>

                                        <div class="price">
                                            <div class="price-prev">$2000</div>
                                            <div class="price-current">$1873</div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-3 no-margin">
                                        <a href="#" class="thumb-holder">
                                            <img alt="" src="assets/images/blank.gif"
                                                 data-echo="assets/images/products/product-small-09.jpg"/>
                                        </a>
                                    </div>

                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- /.body -->
                </div>
                <!-- /.widget -->
                <!-- ============================================================= TOP RATED PRODUCTS : END ============================================================= -->
            </div>
            <!-- /.col -->

        </div>
        <!-- /.widgets-row-->
    </div>
    <!-- /.container -->

    <div class="sub-form-row">
        <div class="container">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2 no-padding">
                <form role="form">
                    <input placeholder="Nhập mã số để tra nguồn gốc sản phẩm">
                    <button class="le-button">Tra cứu</button>
                </form>
            </div>
        </div>
        <!-- /.container -->
    </div>
    <!-- /.sub-form-row -->