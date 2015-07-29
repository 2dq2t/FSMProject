<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 09/06/2015
 * Time: 12:23 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php
$cart_info = Yii::$app->Header->cartInfo();
$total_product = $cart_info['total_product'];
$total_price = $cart_info['total_price'];
$product_in_cart = $cart_info['product_in_cart'];
?>
<div id="cart" class="btn-group btn-block">
    <button type="button" data-toggle="dropdown" data-loading-text="Loading..."
            class="btn btn-inverse btn-block btn-lg dropdown-toggle"><span
            id="cart-total"><?= $total_product . " " . Yii::t('app', 'ProductLabel') . " - " . number_format($total_price) . " " . Yii::t('app', 'VNDLabel') ?></span>
    </button>
    <ul class="dropdown-menu pull-right cart-menu">
        <?php if (empty($product_in_cart)) {
            echo "<li>";
            echo "<p class='text-center'>" . Yii::t('app', 'ShoppingCart_MSG01') . "</p>";
            echo "</li>";
        } else { ?>
            <li class="detail">
                <table class="table table-striped">
                    <?php foreach ($product_in_cart as $product) { ?>
                        <tr>
                            <td class="text-center col-sm-1"><a
                                    href="<?php echo $baseUrl . 'index.php?r=site/view-detail&product=' . $product['product_name'] ?>"><img
                                        style="width: 47px;height: 47px"
                                        src="<?php echo $product['product_image'] ?>"
                                        alt="<?= $product['product_name'] ?>" title="<?= $product['product_name'] ?>"
                                        class="img-thumbnail"/></a>
                            </td>
                            <td class="text-left"><a
                                    href="<?php echo $baseUrl . 'index.php?r=site/view-detail&product=' . $product['product_name'] ?>"><?= $product['product_name'] ?></a>
                            </td>
                            <td class="text-right">x <?= $product['product_quantity'] ?></td>
                            <td class="text-right"><?= number_format($product['product_price'] * $product['product_quantity']) ?></td>
                            <td class="text-center">
                                <button type="button" onclick="cart.remove(<?= $product['product_id'] ?>);"
                                        title="Remove" class="btn btn-danger btn-xs"><i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </li>
            <li>
                <div>
                    <table class="table table-bordered">
                        <tr>
                            <td class="text-right"><strong><?= Yii::t('app', 'SumPriceLabel') ?></strong></td>
                            <td class="text-right"><strong><?= number_format($total_price) ?></strong></td>
                        </tr>
                    </table>
                    <div class="text-right button-container">
                        <a href="<?= $baseUrl . "/index.php?r=site/checkout" ?>"
                           class="checkout"><strong><?= Yii::t('app', 'CheckoutLabel') ?></strong></a>
                        <a href="<?= $baseUrl . "/index.php?r=site/view-cart" ?>"
                           class="addtocart"><strong><?= Yii::t('app', 'ViewCartLabel') ?></strong></a>
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
