<?php
use kartik\alert\Alert;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'HomeTitle');
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php
require('_header.php');
?>

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
<div class="container content-inner">
    <div class="row content-subinner">
        <div class="content-top">
            <div id="content">
                <div class="main-slider">
                    <div id="spinner"></div>
                    <div id="slideShow" class="owl-carousel" style="opacity: 1;">
                        <?php
                        if (!empty($slide_show['product_name'])) {
                            foreach ($slide_show as $slide_show) {
                                echo "<div class='item'>";
                                echo "<a href='" . Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $slide_show['product_name'] . "'><img
                                    src='uploads/slideshow/" . $slide_show['slide_show_id'] . "/" . $slide_show['slide_show_path'] . "'
                                    alt='" . $slide_show['product_name'] . "' class='img-responsive'/></a>";
                                echo "</div> ";
                            }
                        }else {
                            echo "<div class='item'>";
                            echo "<a href='#'><img
                                    src='images/data/no-slide-available.png'class='img-responsive'/></a>";
                            echo "</div> ";
                        }?>
                    </div>
                </div>
                <script type="text/javascript">
                    $('#slideShow').owlCarousel({
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
                                src="images/data/topbanner2.jpg"
                                alt="Topbanner2" class="img-responsive"/></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="content_bottom">
                <div>
                    <column id="column-left" class="col-sm-3 hidden-xs">

                        <?php
                        require('_category.php');
                        echo $this->render('_leftBanner');
                        require('_specialProduct.php');
                        require('_bestSeller.php');
                        ?>

                    </column>
                    <div id="content" class="col-sm-9">
                        <div class="box">
                            <div class="box-heading"><?= Yii::t('app', 'NewProductLabel') ?></div>
                            <div class="box-content">
                                <div class="customNavigation">
                                    <a class="prev">&nbsp;</a>
                                    <a class="next">&nbsp;</a>
                                </div>

                                <div class="box-product product-carousel " id="featured-carousel">

                                    <?php foreach ($new_product as $product) { ?>
                                        <div class="slider-item">
                                            <div class="product-block product-thumb transition">
                                                <div class="product-block-inner ">
                                                    <div class="image">
                                                        <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><img
                                                                src="<?= $product['product_image'] ?>"
                                                                alt="<?= $product['product_name'] ?>"
                                                                title="<?= $product['product_name'] ?>"
                                                                class="img-responsive"/></a>
                                                        <?php
                                                        if (isset($product['product_offer']) && ($product['product_offer'] > 0))
                                                            echo "<span class='saleicon sale'>" . Yii::t('app', 'SaleLabel') . "</span>";

                                                        if (isset($product['product_rating']) && $product['product_rating'] > 0) {
                                                            echo "<div class='rating' >";
                                                            for ($i = 0; $i < 5; $i++) {
                                                                if ($i < $product['product_rating']) {
                                                                    echo "<span class='fa fa-stack' ><i class='fa fa-star fa-stack-2x'' ></i ></span >";
                                                                } else
                                                                    echo "<span class='fa fa-stack' ><i class='fa fa-star off fa-stack-2x' ></i ></span >";
                                                            }
                                                            echo "</div >";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="product-details">
                                                        <div class="caption">
                                                            <h4>
                                                                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"
                                                                   title="<?= $product['product_name'] ?>">
                                                                    <?= $product['product_name'] ?></a>
                                                            </h4>

                                                            <div class="price">
                                                                <?php
                                                                if (!empty($product['product_offer'])) {
                                                                    echo "<span class='price-old'>" . number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                    $new_price = Yii::$app->CommonFunction->sellingPrice($product['product_price'], $product['product_offer']);
                                                                    echo "<span class='price-new'>" . number_format($new_price) . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                    echo "<span class='price-tax'>" . Yii::t('app', 'TaxLabel') . ": " . number_format($product['product_tax']) . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                } else {
                                                                    echo number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel');
                                                                    echo "<span class='price-tax'>" . Yii::t('app', 'TaxLabel') . ": " . number_format($product['product_tax']) . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="button-group">
                                                            <button type="button"
                                                                    title="<?= Yii::t('app', 'AddToCartLabel') ?>"
                                                                    class="addtocart"
                                                                    onclick="cart.add('<?= $product['product_id'] ?>');">
                                                                <span><?= Yii::t('app', 'AddToCartLabel') ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <span class="featured_default_width" style="display:none; visibility:hidden"></span>

                        <div class="box latest">
                            <div class="box-heading"><?= Yii::t('app', 'SeasonProductLabel') ?></div>
                            <div class="box-content">
                                <div class="customNavigation">
                                    <a class="prev">&nbsp;</a>
                                    <a class=" next">&nbsp;</a>
                                </div>


                                <div class="box-product product-carousel" id="latest-carousel">
                                    <?php foreach ($product_season as $product) { ?>
                                        <div class="slider-item">
                                            <div class="product-block product-thumb transition">
                                                <div class="product-block-inner ">
                                                    <div class="image">
                                                        <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><img
                                                                src="<?= $product['product_image'] ?>"
                                                                alt="<?= $product['product_name'] ?>"
                                                                title="<?= $product['product_name'] ?>"
                                                                class="img-responsive"/></a>
                                                        <?php
                                                        if (isset($product['product_offer']) && ($product['product_offer'] > 0))
                                                            echo "<span class='saleicon sale'>" . Yii::t('app', 'SaleLabel') . "</span>";

                                                        if (isset($product['product_rating']) && $product['product_rating'] > 0) {
                                                            echo "<div class='rating' >";
                                                            for ($i = 0; $i < 5; $i++) {
                                                                if ($i < $product['product_rating']) {
                                                                    echo "<span class='fa fa-stack' ><i class='fa fa-star fa-stack-2x'' ></i ></span >";
                                                                } else
                                                                    echo "<span class='fa fa-stack' ><i class='fa fa-star off fa-stack-2x' ></i ></span >";
                                                            }
                                                            echo "</div >";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="product-details">
                                                        <div class="caption">
                                                            <h4>
                                                                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"
                                                                   title="<?= $product['product_name'] ?>">
                                                                    <?= $product['product_name'] ?></a>
                                                            </h4>

                                                            <div class="price">
                                                                <?php
                                                                if (!empty($product['product_offer'])) {
                                                                    echo "<span class='price-old'>" . $product['product_price'] . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                    $new_price = Yii::$app->CommonFunction->sellingPrice($product['product_price'], $product['product_offer']);
                                                                    echo "<span class='price-new'>" . $new_price . Yii::t('app', 'VNDLabel') . "</span>";
                                                                    echo "<span class='price-tax'>" . Yii::t('app', 'TaxLabel') . ": " . $product['product_tax'] . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                } else {
                                                                    echo $product['product_price'] . " " . Yii::t('app', 'VNDLabel');
                                                                    echo "<span class='price-tax'>" . Yii::t('app', 'TaxLabel') . ": " . $product['product_tax'] . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="button-group">
                                                            <button type="button"
                                                                    title="<?= Yii::t('app', 'AddToCartLabel') ?>"
                                                                    class="addtocart"
                                                                    onclick="cart.add('<?= $product['product_id'] ?>');">
                                                                <span><?= Yii::t('app', 'AddToCartLabel') ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <span class="latest_default_width" style="display:none; visibility:hidden"></span>

                        <div id="banner3" class="banner">
                            <div class="item">
                                <a href="#"><img
                                        src="images/data/subbanner1-289x49.png"
                                        alt="subbanner1" class="img-responsive"/></a>
                            </div>
                        </div>
                        <div id="banner4" class="banner">
                            <div class="item">
                                <a href="#"><img
                                        src="images/data/subbanner2-289x49.png"
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
