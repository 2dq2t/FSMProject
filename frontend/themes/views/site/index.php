<?php
use kartik\alert\Alert;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Fresh Garden';
$baseUrl = Yii::$app->request->baseUrl;
?>
<div class="container content-inner">
    <div class="row content-subinner">
        <div class="content-top">
            <div id="content">
                <div class="main-slider">
                    <div id="spinner"></div>
                    <div id="slideshow0" class="owl-carousel" style="opacity: 1;">
                        <div class="item">
                            <a href="#"><img
                                    src="images/data/mainbanner2.jpg"
                                    alt="mainbanner2" class="img-responsive"/></a>
                        </div>
                        <div class="item">
                            <a href="#"><img
                                    src="images/data/mainbanner3.jpg"
                                    alt="mainbanner3" class="img-responsive"/></a>
                        </div>
                        <div class="item">
                            <a href="#"><img
                                    src="images/data/mainbanner1.jpg"
                                    alt="mainbanner1" class="img-responsive"/></a>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $('#slideshow0').owlCarousel({
                        items: 6,
                        autoPlay: 3000,
                        singleItem: true,
                        navigation: true,
                        navigationText: "",
                        pagination: true
                    });
                </script>
                <script type="text/javascript">
                    // Can also be used with $(document).ready()
                    $(window).load(function () {
                        $("#spinner").fadeOut("slow");
                    });
                </script>
                <div id="banner1" class="banner">
                    <div class="item">
                        <a href="#"><img
                                src="images/data/topbanner1.jpg"
                                alt="Topbanner1" class="img-responsive"/></a>
                    </div>
                </div>
                <div id="banner2" class="banner">
                    <div class="item">
                        <a href="#"><img
                                src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/topbanner2-250x196.jpg"
                                alt="Topbanner2" class="img-responsive"/></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="content_bottom">
                <div>
                    <column id="column-left" class="col-sm-3 hidden-xs">
                        <div class="box">
                            <div class="box-heading">Categories</div>
                            <div class="box-content ">
                                <ul class="box-category treeview-list treeview">
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
                                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20_26">PC</a>
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
                        </div>
                        <div id="banner0" class="banner">
                            <div class="item">
                                <a href="#"><img
                                        src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/leftbanner-211x270.png"
                                        alt="Left Banner" class="img-responsive"/></a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-heading">Specials</div>
                            <div class="box-content">


                                <div class="box-product product-grid" id="special-grid">
                                    <div class="product-items">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/12-80x80.jpg"
                                                            alt="Aliquam volutpat" title="Aliquam volutpat"
                                                            class="img-responsive"/></a>
                                                    <span class="saleicon sale">Sale</span>

                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30"
                                                               title="Aliquam volutpat">
                                                                Aliquam volutpat </a>
                                                        </h4>

                                                        <div class="price">
                                                            <span class="price-old">$122.00</span><span
                                                                class="price-new">$98.00</span>
                                                            <span class="price-tax">Ex Tax: $80.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" class="addtocart"
                                                                onclick="cart.add('30');"><span>Add to Cart</span>
                                                        </button>
                                                        <button class="wishlist" type="button" data-toggle="tooltip"
                                                                title="Add to Wish List" onclick="wishlist.add('30');">
                                                            <i class="fa fa-heart"></i></button>
                                                        <button class="compare" type="button" data-toggle="tooltip"
                                                                title="Add to Compare" onclick="compare.add('30');"><i
                                                                class="fa fa-exchange"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-items">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=42"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/17-80x80.jpg"
                                                            alt="Donec sempr sem " title="Donec sempr sem "
                                                            class="img-responsive"/></a>
                                                    <span class="saleicon sale">Sale</span>

                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=42"
                                                               title="Donec sempr sem ">
                                                                Donec sempr sem </a>
                                                        </h4>

                                                        <div class="price">
                                                            <span class="price-old">$122.00</span><span
                                                                class="price-new">$110.00</span>
                                                            <span class="price-tax">Ex Tax: $90.00</span>
                                                        </div>
                                                        <div class="rating">
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" class="addtocart"
                                                                onclick="cart.add('42');"><span>Add to Cart</span>
                                                        </button>
                                                        <button class="wishlist" type="button" data-toggle="tooltip"
                                                                title="Add to Wish List" onclick="wishlist.add('42');">
                                                            <i class="fa fa-heart"></i></button>
                                                        <button class="compare" type="button" data-toggle="tooltip"
                                                                title="Add to Compare" onclick="compare.add('42');"><i
                                                                class="fa fa-exchange"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <span class="special_default_width" style="display:none; visibility:hidden"></span>

                        <div class="box">
                            <div class="box-heading">Bestsellers</div>
                            <div class="box-content">


                                <div class="box-product product-grid" id="bestseller-grid">
                                    <div class="product-items">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=43"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-80x80.jpg"
                                                            alt="Nullam molli dolor " title="Nullam molli dolor "
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=43"
                                                               title="Nullam molli dolor ">
                                                                Nullam molli dolor </a>
                                                        </h4>

                                                        <div class="rating">
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                        </div>
                                                        <p class="price">
                                                            $602.00 <span class="price-tax">Ex Tax: $500.00</span>
                                                        </p>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" class="addtocart"
                                                                onclick="cart.add('43');"><span>Add to Cart</span>
                                                        </button>
                                                        <button class="wishlist" type="button" data-toggle="tooltip"
                                                                title="Add to Wish List" onclick="wishlist.add('43');">
                                                            <i class="fa fa-heart"></i></button>
                                                        <button class="compare" type="button" data-toggle="tooltip"
                                                                title="Add to Compare" onclick="compare.add('43');"><i
                                                                class="fa fa-exchange"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-items">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/18-80x80.jpg"
                                                            alt="Cum sociis natoqu" title="Cum sociis natoqu"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40"
                                                               title="Cum sociis natoqu">
                                                                Cum sociis natoqu </a>
                                                        </h4>

                                                        <p class="price">
                                                            $123.20 <span class="price-tax">Ex Tax: $101.00</span>
                                                        </p>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" class="addtocart"
                                                                onclick="cart.add('40');"><span>Add to Cart</span>
                                                        </button>
                                                        <button class="wishlist" type="button" data-toggle="tooltip"
                                                                title="Add to Wish List" onclick="wishlist.add('40');">
                                                            <i class="fa fa-heart"></i></button>
                                                        <button class="compare" type="button" data-toggle="tooltip"
                                                                title="Add to Compare" onclick="compare.add('40');"><i
                                                                class="fa fa-exchange"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <span class="bestseller_default_width" style="display:none; visibility:hidden"></span>
                    </column>
                    <div id="content" class="col-sm-9">
                        <div class="box">
                            <div class="box-heading">Featured</div>
                            <div class="box-content">
                                <div class="customNavigation">
                                    <a class="prev">&nbsp;</a>
                                    <a class="next">&nbsp;</a>
                                </div>


                                <div class="box-product product-carousel" id="featured-carousel">
                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=43"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-220x200.jpg"
                                                            alt="Nullam molli dolor " title="Nullam molli dolor "
                                                            class="img-responsive"/></a>


                                                    <div class="rating">
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                    </div>
                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=43"
                                                               title="Nullam molli dolor ">
                                                                Nullam molli dolor </a>
                                                        </h4>

                                                        <div class="price">
                                                            $602.00 <span class="price-tax">Ex Tax: $500.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('43');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/18-220x200.jpg"
                                                            alt="Cum sociis natoqu" title="Cum sociis natoqu"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40"
                                                               title="Cum sociis natoqu">
                                                                Cum sociis natoqu </a>
                                                        </h4>

                                                        <div class="price">
                                                            $123.20 <span class="price-tax">Ex Tax: $101.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('40');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=42"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/17-220x200.jpg"
                                                            alt="Donec sempr sem " title="Donec sempr sem "
                                                            class="img-responsive"/></a>
                                                    <span class="saleicon sale">Sale</span>

                                                    <div class="rating">
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                    </div>
                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=42"
                                                               title="Donec sempr sem ">
                                                                Donec sempr sem </a>
                                                        </h4>

                                                        <div class="price">
                                                            <span class="price-old">$122.00</span><span
                                                                class="price-new">$110.00</span>
                                                            <span class="price-tax">Ex Tax: $90.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('42');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=47"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/13-220x200.jpg"
                                                            alt="Aliquam suscipit" title="Aliquam suscipit"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=47"
                                                               title="Aliquam suscipit">
                                                                Aliquam suscipit </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('47');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=28"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/15-220x200.jpg"
                                                            alt="Arcu vitae imperdiet " title="Arcu vitae imperdiet "
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=28"
                                                               title="Arcu vitae imperdiet ">
                                                                Arcu vitae imperdiet.. </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('28');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/12-220x200.jpg"
                                                            alt="Aliquam volutpat" title="Aliquam volutpat"
                                                            class="img-responsive"/></a>
                                                    <span class="saleicon sale">Sale</span>

                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30"
                                                               title="Aliquam volutpat">
                                                                Aliquam volutpat </a>
                                                        </h4>

                                                        <div class="price">
                                                            <span class="price-old">$122.00</span><span
                                                                class="price-new">$98.00</span>
                                                            <span class="price-tax">Ex Tax: $80.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('30');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=41"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/25-220x200.jpg"
                                                            alt="Consectetur adipiscing" title="Consectetur adipiscing"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=41"
                                                               title="Consectetur adipiscing">
                                                                Consectetur adipisci.. </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('41');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=49"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/8-220x200.jpg"
                                                            alt="Tellus volutpat ius" title="Tellus volutpat ius"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=49"
                                                               title="Tellus volutpat ius">
                                                                Tellus volutpat ius </a>
                                                        </h4>

                                                        <div class="price">
                                                            $241.99 <span class="price-tax">Ex Tax: $199.99</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('49');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">
                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=46"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/6-220x200.jpg"
                                                            alt="Suspene potention" title="Suspene potention"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=46"
                                                               title="Suspene potention">
                                                                Suspene potention </a>
                                                        </h4>

                                                        <div class="price">
                                                            $1,202.00 <span class="price-tax">Ex Tax: $1,000.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('46');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <span class="featured_default_width" style="display:none; visibility:hidden"></span>

                        <div class="box latest">
                            <div class="box-heading">Latest</div>
                            <div class="box-content">
                                <div class="customNavigation">
                                    <a class="prev">&nbsp;</a>
                                    <a class=" next">&nbsp;</a>
                                </div>


                                <div class="box-product product-carousel" id="latest-carousel">
                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=49"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/8-220x200.jpg"
                                                            alt="Tellus volutpat ius" title="Tellus volutpat ius"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=49"
                                                               title="Tellus volutpat ius">
                                                                Tellus volutpat ius </a>
                                                        </h4>

                                                        <div class="price">
                                                            $241.99 <span class="price-tax">Ex Tax: $199.99</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('49');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=48"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/19-220x200.jpg"
                                                            alt="Ectus  rhoncusout" title="Ectus  rhoncusout"
                                                            class="img-responsive"/></a>


                                                    <div class="rating">
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                    </div>
                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=48"
                                                               title="Ectus  rhoncusout">
                                                                Ectus rhoncusout </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                        <div class="rating">
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('48');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=47"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/13-220x200.jpg"
                                                            alt="Aliquam suscipit" title="Aliquam suscipit"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=47"
                                                               title="Aliquam suscipit">
                                                                Aliquam suscipit </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('47');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=46"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/6-220x200.jpg"
                                                            alt="Suspene potention" title="Suspene potention"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=46"
                                                               title="Suspene potention">
                                                                Suspene potention </a>
                                                        </h4>

                                                        <div class="price">
                                                            $1,202.00 <span class="price-tax">Ex Tax: $1,000.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('46');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=45"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/26-220x200.jpg"
                                                            alt="Pellentesque augue" title="Pellentesque augue"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=45"
                                                               title="Pellentesque augue">
                                                                Pellentesque augue </a>
                                                        </h4>

                                                        <div class="price">
                                                            $2,000.00 <span class="price-tax">Ex Tax: $2,000.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('45');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=44"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/24-220x200.jpg"
                                                            alt="Palm Treo Pro" title="Palm Treo Pro"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=44"
                                                               title="Palm Treo Pro">
                                                                Palm Treo Pro </a>
                                                        </h4>

                                                        <div class="price">
                                                            $1,202.00 <span class="price-tax">Ex Tax: $1,000.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('44');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=43"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-220x200.jpg"
                                                            alt="Nullam molli dolor " title="Nullam molli dolor "
                                                            class="img-responsive"/></a>


                                                    <div class="rating">
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                    </div>
                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=43"
                                                               title="Nullam molli dolor ">
                                                                Nullam molli dolor </a>
                                                        </h4>

                                                        <div class="price">
                                                            $602.00 <span class="price-tax">Ex Tax: $500.00</span>
                                                        </div>
                                                        <div class="rating">
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('43');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=42"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/17-220x200.jpg"
                                                            alt="Donec sempr sem " title="Donec sempr sem "
                                                            class="img-responsive"/></a>
                                                    <span class="saleicon sale">Sale</span>

                                                    <div class="rating">
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                                                        <span class="fa fa-stack"><i
                                                                class="fa fa-star off fa-stack-2x"></i></span>
                                                    </div>
                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=42"
                                                               title="Donec sempr sem ">
                                                                Donec sempr sem </a>
                                                        </h4>

                                                        <div class="price">
                                                            <span class="price-old">$122.00</span><span
                                                                class="price-new">$110.00</span>
                                                            <span class="price-tax">Ex Tax: $90.00</span>
                                                        </div>
                                                        <div class="rating">
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star fa-stack-2x"></i></span>
                                                            <span class="fa fa-stack"><i
                                                                    class="fa fa-star off fa-stack-2x"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('42');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=41"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/25-220x200.jpg"
                                                            alt="Consectetur adipiscing" title="Consectetur adipiscing"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=41"
                                                               title="Consectetur adipiscing">
                                                                Consectetur adipisci.. </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('41');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/18-220x200.jpg"
                                                            alt="Cum sociis natoqu" title="Cum sociis natoqu"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40"
                                                               title="Cum sociis natoqu">
                                                                Cum sociis natoqu </a>
                                                        </h4>

                                                        <div class="price">
                                                            $123.20 <span class="price-tax">Ex Tax: $101.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('40');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=36"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/20-220x200.jpg"
                                                            alt="Integer tempor lacus " title="Integer tempor lacus "
                                                            class="img-responsive"/></a>
                                                    <span class="saleicon sale">Sale</span>

                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=36"
                                                               title="Integer tempor lacus ">
                                                                Integer tempor lacus.. </a>
                                                        </h4>

                                                        <div class="price">
                                                            <span class="price-old">$254.00</span><span
                                                                class="price-new">$248.00</span>
                                                            <span class="price-tax">Ex Tax: $205.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('36');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=35"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/27-220x200.jpg"
                                                            alt="Praesent semneck" title="Praesent semneck"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=35"
                                                               title="Praesent semneck">
                                                                Praesent semneck </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('35');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=34"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/21-220x200.jpg"
                                                            alt="Justo neque commodo " title="Justo neque commodo "
                                                            class="img-responsive"/></a>
                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=34"
                                                               title="Justo neque commodo ">
                                                                Justo neque commodo </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('34');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=33"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/9-220x200.jpg"
                                                            alt="Vactramn denim" title="Vactramn denim"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=33"
                                                               title="Vactramn denim">
                                                                Vactramn denim </a>
                                                        </h4>

                                                        <div class="price">
                                                            $242.00 <span class="price-tax">Ex Tax: $200.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('33');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=32"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/22-220x200.jpg"
                                                            alt="Nascetur ridiculus mus" title="Nascetur ridiculus mus"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=32"
                                                               title="Nascetur ridiculus mus">
                                                                Nascetur ridiculus m.. </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('32');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=31"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/3-220x200.jpg"
                                                            alt="Praesent fringilla" title="Praesent fringilla"
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=31"
                                                               title="Praesent fringilla">
                                                                Praesent fringilla </a>
                                                        </h4>

                                                        <div class="price">
                                                            $98.00 <span class="price-tax">Ex Tax: $80.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('31');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/12-220x200.jpg"
                                                            alt="Aliquam volutpat" title="Aliquam volutpat"
                                                            class="img-responsive"/></a>
                                                    <span class="saleicon sale">Sale</span>

                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30"
                                                               title="Aliquam volutpat">
                                                                Aliquam volutpat </a>
                                                        </h4>

                                                        <div class="price">
                                                            <span class="price-old">$122.00</span><span
                                                                class="price-new">$98.00</span>
                                                            <span class="price-tax">Ex Tax: $80.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('30');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=29"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/4-220x200.jpg"
                                                            alt="Suspendisse massa " title="Suspendisse massa "
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=29"
                                                               title="Suspendisse massa ">
                                                                Suspendisse massa </a>
                                                        </h4>

                                                        <div class="price">
                                                            $337.99 <span class="price-tax">Ex Tax: $279.99</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('29');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slider-item">
                                        <div class="product-block product-thumb transition">
                                            <div class="product-block-inner ">

                                                <div class="image">
                                                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=28"><img
                                                            src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/15-220x200.jpg"
                                                            alt="Arcu vitae imperdiet " title="Arcu vitae imperdiet "
                                                            class="img-responsive"/></a>


                                                </div>
                                                <div class="product-details">
                                                    <div class="caption">
                                                        <h4>
                                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=28"
                                                               title="Arcu vitae imperdiet ">
                                                                Arcu vitae imperdiet.. </a>
                                                        </h4>

                                                        <div class="price">
                                                            $122.00 <span class="price-tax">Ex Tax: $100.00</span>
                                                        </div>
                                                    </div>

                                                    <div class="button-group">
                                                        <button type="button" title="Add to Cart" class="addtocart"
                                                                onclick="cart.add('28');"><span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <span class="latest_default_width" style="display:none; visibility:hidden"></span>

                        <div id="banner3" class="banner">
                            <div class="item">
                                <a href="#"><img
                                        src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/subbanner1-289x49.png"
                                        alt="subbanner1" class="img-responsive"/></a>
                            </div>
                        </div>
                        <div id="banner4" class="banner">
                            <div class="item">
                                <a href="#"><img
                                        src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/subbanner2-289x49.png"
                                        alt="subbanner2" class="img-responsive"/></a>
                            </div>
                        </div>
                        <div id="carousel-0" class="banners-slider-carousel">
                            <div class="customNavigation">
                                <a class="prev t"></a>
                                <a class="next"></a>
                            </div>
                            <div class="product-carousel" id="module-0-carousel">
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-8-137x90-137x90.png"
                                                    alt="Nintendo"/></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-2-137x90-137x90.png"
                                                    alt="Manufacturers1"/></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-6-137x90-137x90.png"
                                                    alt="Burger King"/></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-8-137x90-137x90.png"
                                                    alt="Canon"/></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-3-137x90-137x90.png"
                                                    alt="Harley Davidson"/></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-5-137x90-137x90.png"
                                                    alt="Dell"/></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-4-137x90-137x90.png"
                                                    alt="Disney"/></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-item">
                                    <div class="product-block">
                                        <div class="product-block-inner">
                                            <a href="#"><img
                                                    src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/logo-3-137x90-137x90.png"
                                                    alt="Starbucks"/></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="module_default_width" style="display:none; visibility:hidden"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
