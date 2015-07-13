<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
$this->title = Yii::t('app', 'ShoppingCartLabel');
?>
<?php
$cart_info = Yii::$app->Header->cartInfo();
$total_product = $cart_info['total_product'];
$total_price = $cart_info['total_price'];
$product_in_cart = $cart_info['product_in_cart']
?>
<?php
require('_header.php');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=site/view-cart"; ?>"
               title="Giỏ hàng"><?= Yii::t('app', 'ShoppingCartLabel') ?></a>
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
        <div id="content" class="col-sm-9">
            <h1 id="wish_list"
                class="page-title"><?= Yii::t('app', 'ShoppingCartLabel') ?><?php if (!Yii::$app->user->isGuest) {
                    $number_product = Yii::$app->Header->numberProductWishList(Yii::$app->user->identity->getId());
                    echo " (" . $number_product . ")";
                } ?>
            </h1>
            <?php if (empty($product_in_cart)) { ?>
                <p><?= Yii::t('app', 'ShoppingCart_MSG01') ?></p>
                <div class="buttons">
                    <div class="pull-right"><a
                            href="<?= $baseUrl ?>"
                            class="btn btn-primary"><?= Yii::t('app', 'ContinueShoppingLabel') ?></a></div>
                </div>
            <?php } else { ?>
                <form action="<?= $baseUrl . "/index.php?r=site/update-cart" ?>"
                      method="post" enctype="multipart/form-data">

                    <div class="table-responsive">
                        <table class="table table-bordered shopping-cart">
                            <thead>
                            <tr>
                                <td class="text-center col-sm-2"><?= Yii::t('app', 'ImageLabel') ?></td>
                                <td class="text-left"><?= Yii::t('app', 'ProductNameLabel') ?></td>
                                <td class="text-left"><?= Yii::t('app', 'QuantityLabel') ?></td>
                                <td class="text-right"><?= Yii::t('app', 'ProductPrice') ?></td>
                                <td class="text-right"><?= Yii::t('app', 'SumPriceLabel') ?></td>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($product_in_cart as $product) { ?>
                                <tr id="<?= "product" . $product['product_id'] ?>">
                                    <td class="text-center  col-sm-2 row-sm-2">
                                        <a href="<?= $baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><img
                                                style="width: 94px;height: 94px"
                                                src="<?= $product['product_image'] ?>"
                                                alt="<?= $product['product_name'] ?>"
                                                title="<?= $product['product_name'] ?>" class="img-thumbnail"/></a>
                                    </td>
                                    <td class="text-left"><a
                                            href="<?= $baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><?= $product['product_name'] ?></a>
                                        <br/>
                                    </td>
                                    <td class="text-left">
                                        <div class="input-group btn-block" style="max-width: 200px;">
                                            <input class="form-control" type="number" min="1"
                                                   name="update_cart[<?= $product['product_id'] ?>]"
                                                   value="<?= $product['product_quantity'] ?>">
                                            <span class="input-group-btn">
                                                <button type="submit" data-toggle="tooltip" title=""
                                                        class="btn btn-primary"
                                                        data-original-title="<?= Yii::t('app', 'UpdateLabel') ?>"><i
                                                        class="fa fa-refresh"></i>
                                                </button>
                                                <button id="<?= "remove" . $product['product_id'] ?>" type="button"
                                                        data-toggle="tooltip"
                                                        title="<?= Yii::t('app', 'DeleteLabel') ?>"
                                                        class="btn btn-danger"
                                                        onclick="cart.remove(<?= $product['product_id'] ?>);"><i
                                                        class="fa fa-times-circle"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-right"><?php
                                        echo number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel');
                                        ?></td>
                                    <td class="text-right"><?php $total_price_of_one_product = $product['product_price'] * $product['product_quantity'];
                                        echo number_format($total_price_of_one_product) . " " . Yii::t('app', 'VNDLabel');
                                        ?></td>

                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <h2><?= Yii::t('app', 'ShoppingCartNotice01') ?></h2>

                <p><?= Yii::t('app', 'ShoppingCartNotice02') ?></p>

                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><a href="#collapse-coupon" class="accordion-toggle"
                                                       data-toggle="collapse"
                                                       data-parent="#accordion"><?= Yii::t('app', 'ShoppingCartNotice03') ?>
                                    <i class="fa fa-caret-down"></i></a></h4>
                        </div>
                        <div id="collapse-coupon" class="panel-collapse collapse">
                            <div class="panel-body">
                                <label class="col-sm-2 control-label"
                                       for="input-coupon"><?= Yii::t('app', 'ShoppingCartNotice03') ?></label>

                                <div class="input-group">
                                    <input type="text" name="voucher" value=""
                                           placeholder="<?= Yii::t('app', 'ShoppingCartNotice04') ?>"
                                           id="input-voucher" class="form-control">
                                    <span class="input-group-btn">
                                    <input type="button" value="<?= Yii::t('app', 'ApplyLabel') ?>" id="button-voucher"
                                           data-loading-text="Loading..."
                                           class="btn btn-primary">
                                    </span>
                                </div>
                                <script type="text/javascript"><!--
                                    $('#button-voucher').on('click', function () {
                                        $.ajax({
                                            url: 'index.php?r=site/voucher',
                                            type: 'post',
                                            data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
                                            dataType: 'json',
                                            beforeSend: function () {
                                                $('#button-voucher').button('loading');
                                            },
                                            complete: function () {
                                                $('#button-voucher').button('reset');
                                            },
                                            success: function (json) {
                                                $('.alert').remove();

                                                if (json['error']) {
                                                    $('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                                    $('html, body').animate({scrollTop: 0}, 'slow');
                                                }

                                                if (json['redirect']) {
                                                    location = json['redirect'];
                                                }
                                            }
                                        });
                                    });
                                    //--></script>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row ">
                    <div class="col-sm-4 col-sm-offset-8">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td class="text-right"><strong><?= Yii::t('app', 'SumPriceLabel') ?>:</strong></td>
                                <td class="text-right"><?= number_format($total_price) . " " . Yii::t('app', 'VNDLabel') ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="buttons">
                    <div class="pull-left"><a
                            href="<?= $baseUrl ?>"
                            class="btn btn-default"><?= Yii::t('app', 'ContinueShoppingLabel') ?></a></div>
                    <div class="pull-right"><a
                            href="<?= $baseUrl . "/index.php?r=site/checkout" ?>"
                            class="btn btn-primary"><?= Yii::t('app', 'CheckoutLabel') ?></a></div>
                </div>
            <?php } ?>
        </div>
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


