<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
$this->title = $product_detail['name'];
?>
<?php require('_header.php');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?php echo $baseUrl . 'index.php?r=site/view-detail&product=' . $product_detail['name'] ?>"><?php echo $product_detail['name'] ?></a>
        </li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php
            require('_category.php');
            echo $this->render('_leftBanner');
            require('_specialProduct.php');
            require('_bestSeller.php');
            ?>
        </column>
        <div id="content" class="productpage col-sm-9">
            <div class="row">
                <div class="col-sm-6 product-left">
                    <div class="product-info">
                        <ul class="left product-image thumbnails">

                            <!-- Megnor Cloud-Zoom Image Effect Start -->
                            <?php
                            if (!empty($product_image_detail[0]['resize_path']))
                                echo "<li class='image'><a class='thumbnail' href='" . $product_image_detail[0]['path'] . "' title='" . $product_detail['name'] . "'><img src='" . $product_image_detail[0]['resize_path'] . "' title='" . $product_detail['name'] . "' alt='" . $product_detail['name'] . "' /></a></li>";
                            ?>

                            <div class="additional-carousel">
                                <div class="customNavigation">
                                    <span class="prev"></span>
                                    <span class="next"></span>
                                </div>

                                <div id="additional-carousel" class="image-additional product-carousel">
                                    <?php
                                    if (!empty($product_image_detail[0]['resize_path'])) {
                                        foreach ($product_image_detail as $image) {

                                            echo "<div class='slider-item'>";
                                            echo "<div class='product-block'>";
                                            echo "<a href='" . $image['path'] . "' title='" . $product_detail['name'] . " ' class='thumbnail elevatezoom-gallery' data-image='" . $image['resize_path'] . "' data-zoom-image='" . $image['path'] . "'><img src='" . $image['resize_path'] . "' width='74' title='" . $product_detail['name'] . " ' alt='" . $product_detail['name'] . "' /></a>";
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="additional_default_width" style="display:none; visibility:hidden"></span>
                            </div>


                            <!-- Megnor Cloud-Zoom Image Effect End-->
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 product-right">
                    <h3 class="product-title"><?php echo $product_detail['name'] ?> </h3>
                    <ul class="list-unstyled">
                        <li><span><?= Yii::t('app', 'ProductCode') ?>: </span><?php echo $product_detail['barcode'] ?>
                        </li>
                        <li>
                            <span><?= Yii::t('app', 'QuantityLabel') ?>
                                : </span><?php if ($product_detail['quantity_in_stock'] - $product_detail['sold'] > 0) echo $product_detail['quantity_in_stock'] - $product_detail['sold']; else echo Yii::t('app', 'ProductStatus'); ?>
                        </li>
                        <li>
                            <span><?= Yii::t('app', 'WeightLabel') ?>
                                : </span><?php if (isset($product_unit['name'])) echo $product_unit['name'] ?>
                        </li>
                        <li><p><span><?= Yii::t('app', 'StandardLabel') ?>: </span><span class="inline"
                                                                                         style="margin-left: 3px"><a
                                        href="http://vietgap.gov.vn/Content.aspx?mode=uc&page=About&Option=7"
                                        target="_blank"
                                        rel="nofollow"><?= Yii::t('app', 'ProductDetailNotice01') ?></a></span></p></li>
                        <li><p><?php echo $product_detail['intro'] ?> </p></li>
                    </ul>


                    <ul class="list-unstyled price">
                        <li class="price-title"><?= Yii::t('app', 'PriceLabel') ?>:</li>
                        <?php
                        if (!empty($product_offer)) {
                            echo "<li>";
                            echo "<span class='old-price' style='text-decoration: line-through;'>" . number_format($product_detail['price']) . " " . Yii::t('app', 'VNDLabel') . "</span>";
                            echo "<li>";
                        } else {
                            echo "<li class='price-normal''>";
                            echo "<h2 >" . number_format($product_detail['price']) . " " . Yii::t('app', 'VNDLabel') . "</h2>";
                            echo "</li>";
                        }
                        if (!empty($product_offer)) {
                            $price_offer = Yii::$app->CommonFunction->sellingPrice($product_detail['price'], $product_offer);;
                            echo "<li>";
                            echo "<h2 class='special-price'>" . number_format($price_offer) . " " . Yii::t('app', 'VNDLabel') . "</h2>";
                            echo "<li>";
                        }
                        ?>
                        <li class="tax price-tax"><?= Yii::t('app', 'TaxLabel') ?>:<span
                                class="price-tax"><?php echo number_format($product_detail['tax']) . " " . Yii::t('app', 'VNDLabel') ?></span>
                        </li>
                        <br/>
                    </ul>
                    <div id="product">

                        <div class="form-group quntity">
                            <label class="control-label" for="input-quantity"><?= Yii::t('app', 'QuantityLabelMin') ?>
                                :</label>
                            <input type="text" name="quantity" value="1" size="2" id="input-quantity"
                                   class="form-control"/>
                            <input type="hidden" id="product_id" name="product_id"
                                   value="<?php echo $product_detail['id'] ?>"/>


                            <button type="button" id="button-cart" data-loading-text="Loading..."
                                    title="<?= Yii::t('app', 'AddToCartLabel') ?>"
                                    class="addtocart"><span><?= Yii::t('app', 'AddToCartLabel') ?></span></button>
                            <span>&nbsp;&nbsp;- OR -&nbsp;&nbsp;</span>

                            <div class="btn-group">
                                <button type="button" class="wishlist" title="Add to Wish List"
                                        onclick="wishlist.add(<?php echo $product_detail['id']; ?> );"><?= Yii::t('app', 'AddToWishListLabel') ?>
                                </button>

                            </div>
                        </div>
                    </div>


                    <div class="rating-wrapper">
                        <div id="star" class="star" data-score="4"></div>
                    </div>

                    <!-- Like Facebook Button -->
                    <div class="fb-like" data-href="<?php echo "localhost/" . Yii::$app->request->getUrl(); ?>"
                         data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
                    <!-- Like Facebook Button END -->

                </div>
                <div class="col-sm-12 product-description">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-description"
                                              data-toggle="tab"><?= Yii::t('app', 'DescriptionLabel') ?></a></li>
                        <li><a href="#tab-review" data-toggle="tab"><?= Yii::t('app', 'FeedbackLabel') ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-description">
                            <p><?php echo $product_detail['description'] ?></p></div>

                        <div class="tab-pane" id="tab-review">
                            <div class="fb-comments"
                                 data-href="<?php echo "localhost/" . Yii::$app->request->getUrl(); ?>"
                                 data-numposts="5" data-colorscheme="light"></div>
                            <!-- /.comments -->
                        </div>
                    </div>
                </div>
                <div id="slide" class="col-sm-12">
                    <div class="box">
                        <div class="box-heading"><?= Yii::t('app', 'ProductSameCatLabel') ?></div>
                        <div class="box-content">
                            <div class="customNavigation">
                                <a class="prev">&nbsp;</a>
                                <a class="next">&nbsp;</a>
                            </div>

                            <div class="box-product product-carousel" id="category-carousel">

                                <?php foreach ($products_same_category as $product) { ?>
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
                                                    if (isset($product['product_offer']) && $product['product_offer'] > 0)
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
                                                                $new_price = $product['product_price'] * (100 / $product['product_offer']);
                                                                echo "<span class='price-new'>" . number_format($new_price) . " VND</span>";
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
                </div>

                <div class="product-tag col-sm-12"><b><?= Yii::t('app', 'TagLabel') ?>:</b>
                    <?php
                    if (!empty($product_tag[0]['name'])) {
                        foreach ($product_tag as $tag) {
                            if ($tag === end($product_tag)) {
                                echo "<a href='" . $baseUrl . "/index.php?r=site/product-tag&tag=" . $tag['name'] . "'>" . $tag['name'] . "</a>";
                            } else {
                                echo "<a href='" . $baseUrl . "/index.php?r=site/product-tag&tag=" . $tag['name'] . "'>" . $tag['name'] . "</a>";
                                echo ", ";
                            }
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    $('#button-cart').on('click', function () {
        $.ajax({
            url: 'index.php?r=site/add-to-cart',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-cart').button('loading');
            },
            complete: function () {
                $('#button-cart').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');
                var json = $.parseJSON(json)
                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                element.parent().before('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                element.before('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['recurring']) {
                        $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('has-error');
                }

                if (json['success']) {
                    $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    //
                    $('#cart-total').html(json['total']);

                    $('html, body').animate({scrollTop: 0}, 'slow');

                    $('#cart > ul').load('index.php?r=site/cart-info ul li');

                    setTimeout(function () {
                        $('.alert').remove();
                    }, 2000);
                }
            }
        });
    });
    //--></script>

<script type="text/javascript"><!--
    $(document).ready(function () {
        $('.thumbnails').magnificPopup({
            type: 'image',
            delegate: 'a',
            gallery: {
                enabled: true
            }
        });
    });

    //-->
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#star').raty({
            score:<?=$rating_average ?>,
            click: function (score, evt) {
                var product_id = document.getElementById('product_id').value;
                $.ajax({
                    url: 'index.php?r=site/rating',
                    type: 'post',
                    data: {score: score, product_id: product_id},
                    dataType: 'json',
                    success: function (data) {
                        $('.alert').remove();
                        var json = $.parseJSON(data)
                        if (json['success']) {
                            $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            //$('#star').raty('reload');
                            setTimeout(function () {
                                $('.alert').remove();
                            }, 2000);
                            $('#star').raty('readOnly', true);
                        }

                        if (json['error']) {
                            $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            setTimeout(function () {
                                $('.alert').remove();
                            }, 2000);
                        }

                        $('html, body').animate({scrollTop: 0}, 'slow');
                    }
                });
            }
        });
    });
</script>

