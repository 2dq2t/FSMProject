<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
$this->title = "Thanh toán";
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
               title="Danh mục yêu thích">Giỏ hàng</a>
        </li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=site/checkout"; ?>"
               title="Danh mục yêu thích">Thanh toán</a>
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
            <h1 class="page-title">Thanh toán</h1>

            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Step 1: Checkout Options <i class="fa fa-caret-down"></i></a></h4>
                    </div>
                    <div class="panel-collapse collapse in" id="collapse-checkout-option" style="height: auto;">
                        <div class="panel-body"><div class="row">
                                <?php \yii\widgets\ActiveForm::begin()?>
                                <div class="col-sm-6">
                                    <h2>New Customer</h2>
                                    <p>Checkout Options:</p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="account" value="register" checked="checked">
                                            Register Account</label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="account" value="guest">
                                            Guest Checkout</label>
                                    </div>
                                    <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
                                    <input type="submit" value="Continue" id="button-account" data-loading-text="Loading..." class="btn btn-primary">
                                </div>
                                <?php \yii\widgets\ActiveForm::end()?>
                                <div class="col-sm-6">
                                    <h2>Returning Customer</h2>
                                    <p>I am a returning customer</p>
                                    <div class="form-group">
                                        <label class="control-label" for="input-email">E-Mail</label>
                                        <input type="text" name="email" value="" placeholder="E-Mail" id="input-email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="input-password">Password</label>
                                        <input type="password" name="password" value="" placeholder="Password" id="input-password" class="form-control">
                                        <div class="forget-password"><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/forgotten">Forgotten Password</a></div></div>
                                    <input type="button" value="Login" id="button-login" data-loading-text="Loading..." class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a href="#collapse-payment-address" data-toggle="collapse"
                                                   data-parent="#accordion" class="accordion-toggle">Thông Tin Chi Tiết <i class="fa fa-caret-down"></i></a></h4>
                    </div>
                    <div class="panel-collapse collapse in" id="collapse-payment-address" style="height: auto;">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <fieldset id="account">
                                        <legend>Thông Tin Cá Nhân</legend>
                                        <div class="form-group required">
                                            <label class="control-label" for="input-payment-firstname">Họ và Tên</label>
                                            <input type="text" name="fullName" <?php if(!empty($personal_info)) echo "value='".$personal_info['full_name']."''";?> placeholder="Họ và Tên"
                                                   id="input-payment-fullName" class="form-control">
                                        </div>
                                        <div class="form-group required">
                                            <label class="control-label" for="input-payment-email">E-Mail</label>
                                            <input type="text" name="email" <?php if(!empty($personal_info)) echo "value='".$personal_info['email']."''";?> placeholder="E-Mail"
                                                   id="input-payment-email" class="form-control">
                                        </div>
                                        <div class="form-group required">
                                            <label class="control-label" for="input-payment-telephone">Số điện thoại</label>
                                            <input type="text" name="telephone" <?php if(!empty($personal_info)) echo "value='".$personal_info['phone_number']."''";?> placeholder="Số điện thoại"
                                                   id="input-payment-telephone" class="form-control">
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6">
                                    <fieldset id="address" class="required">
                                        <legend>Địa chỉ</legend>
                                        <div class="form-group required">
                                            <label class="control-label" for="input-payment-address-1">Địa Chỉ</label>
                                            <input type="text" name="address"  <?php if(!empty($address)) echo "value='".$address."''";?>  placeholder="Địa chỉ"
                                                   id="input-payment-address" class="form-control">
                                        </div>
                                        <div class="form-group required">
                                            <label class="control-label" for="input-payment-city">Quận/Huyện</label>
                                            <input type="text" name="city" value="" placeholder="City"
                                                   id="input-payment-city" class="form-control">
                                        </div>
                                        <div class="form-group required">
                                            <label class="control-label" for="input-payment-postcode">Thành Phố</label>
                                            <input type="text" name="postcode" value="" placeholder="Post Code"
                                                   id="input-payment-postcode" class="form-control">
                                        </div>

                                    </fieldset>
                                </div>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="shipping_address" value="1" checked="checked">
                                    Cập nhật địa chỉ của tôi.</label>
                            </div>
                            <h2><?= Yii::t('app', 'ShoppingCartNotice01') ?></h2>

                            <p><?= Yii::t('app', 'ShoppingCartNotice02') ?></p>
                            <div class="panel-group" id="voucher">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a href="#collapse-coupon" class="accordion-toggle"
                                                                   data-toggle="collapse"
                                                                   data-parent="#voucher"><?= Yii::t('app', 'ShoppingCartNotice03') ?>
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

                            <div class="buttons">
                                <div class="pull-right">
                                    <input type="button" value="Continue" id="button-guest"
                                           data-loading-text="Loading..." class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



