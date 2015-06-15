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
                        <h4 class="panel-title">Step 1: Checkout Options</h4>
                    </div>
                    <div class="panel-collapse collapse" id="collapse-checkout-option">
                        <div class="panel-body"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Step 2: Account &amp; Billing Details</h4>
                    </div>
                    <div class="panel-collapse collapse" id="collapse-payment-address">
                        <div class="panel-body"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Step 3: Delivery Details</h4>
                    </div>
                    <div class="panel-collapse collapse" id="collapse-shipping-address">
                        <div class="panel-body"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Step 6: Confirm Order</h4>
                    </div>
                    <div class="panel-collapse collapse" id="collapse-checkout-confirm">
                        <div class="panel-body"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
        $(document).on('change', 'input[name=\'account\']', function() {
            if ($('#collapse-payment-address').parent().find('.panel-heading .panel-title > *').is('a')) {
                if (this.value == 'register') {
                    $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Step 2: Account &amp; Billing Details <i class="fa fa-caret-down"></i></a>');
                } else {
                    $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Step 2: Billing Details <i class="fa fa-caret-down"></i></a>');
                }
            } else {
                if (this.value == 'register') {
                    $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('Step 2: Account &amp; Billing Details');
                } else {
                    $('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('Step 2: Billing Details');
                }
            }
        });
        $(document).ready(function() {
            $.ajax({
                url: 'index.php?r=site/login',
                dataType: 'html',
                success: function(html) {
                    var html = $.parseJSON(html)
                    $('#collapse-checkout-option .panel-body').html(html);

                    $('#collapse-checkout-option').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">Step 1: Checkout Options <i class="fa fa-caret-down"></i></a>');

                    $('a[href=\'#collapse-checkout-option\']').trigger('click');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });


</script>


