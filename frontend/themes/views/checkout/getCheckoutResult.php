<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */

use kartik\widgets\ActiveForm;
use kartik\alert\Alert;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = Yii::t('app', 'CheckoutLabel');
?>
<?php
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">
    <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
        <?php
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? \yii\helpers\Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? \yii\helpers\Html::encode($message['message']) : Yii::t('app', 'Message Not Set!'),
            'showSeparator' => true,
            'delay' => 6000
        ]);
        ?>
    <?php endforeach; ?>
    <ul class="breadcrumb">
        <li><a href="<?=$baseUrl?>"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=cart/view-cart"; ?>"
               title="Danh mục yêu thích"><?= Yii::t('app', 'ShoppingCartLabel') ?></a>
        </li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=checkout/checkout"; ?>"
               title="Danh mục yêu thích"><?= Yii::t('app', 'CheckoutLabel') ?></a>
        </li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php
            echo $this->render('/layouts/_category.php');
            echo $this->render('/layouts/_leftBanner');
            echo $this->render('/layouts/_specialProduct.php');
            echo $this->render('/layouts/_bestSeller.php');
            ?>
        </column>
        <div id="content" class="col-sm-9">
            <h1 class="page-title"><?= Yii::t('app', 'CheckoutResultLabel') ?></h1>
            <?php if(empty($order['id'])) {
                echo Yii::t('app', 'CheckoutResult Fail');
            }
            ?>
            <?php if(!empty($order['id'])): ?>
            <fieldset id="checkout">
                <legend><?= Yii::t('app', 'CheckoutResult Personal') ?> </legend>
                <div class="col-sm-12">
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult FullName')?></div>
                    <div class="col-sm-8"><?=$customer_info['full_name']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult Email')?></div>
                    <div class="col-sm-8"><?=$customer_info['email']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult PhoneNumber')?></div>
                    <div class="col-sm-8"><?=$customer_info['phone_number']?></div>


                </div>
                </br>
                <legend><?= Yii::t('app', 'CheckoutResult OrderAddress') ?> </legend>
                <div class="col-sm-12">
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult Address')?></div>
                    <div class="col-sm-8"><?=$address['detail']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult District')?></div>
                    <div class="col-sm-8"><?=$district['name']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult City')?></div>
                    <div class="col-sm-8"><?=$city['name']?></div>
                </div>
                </br>
                <legend><?= Yii::t('app', 'CheckoutResult OtherInfo') ?> </legend>
                <div class="col-sm-12">
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult ReceiverDate')?></div>
                    <div class="col-sm-8"><?=date('d/m/Y',$order['receiving_date'])?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult Note')?></div>
                    <?php if($order['description'] != 'null'):?>
                    <div class="col-sm-8"><?=$order['description']?></div>
                    <?php endif ?>
                </div>
                </br>
                <legend><?= Yii::t('app', 'CheckoutResult OrderDetail') ?> </legend>
                <div class="table-responsive">
                    <table class="table table-bordered shopping-cart">
                        <thead>
                        <tr>
                            <td class="text-center col-sm-2"><?= Yii::t('app', 'CheckoutResult Image') ?></td>
                            <td class="text-left"><?= Yii::t('app', 'CheckoutResult ProductName') ?></td>
                            <td class="text-left"><?= Yii::t('app', 'CheckoutResult Quantity') ?></td>
                            <td class="text-right"><?= Yii::t('app', 'CheckoutResult ProductPrice') ?></td>
                            <td class="text-right"><?= Yii::t('app', 'CheckoutResult SumPrice') ?></td>

                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($order_detail as $product) { ?>
                            <tr >
                                <td class="text-center  col-sm-2 row-sm-2">
                                    <a href=""><img
                                            style="width: 54px;height: 54px"
                                            src="<?= $product['product_image'] ?>"
                                            alt="<?= $product['product_name'] ?>"
                                            title="<?= $product['product_name'] ?>" class="img-thumbnail"/></a>
                                </td>
                                <td class="text-left"><a
                                        href=""><?= ucwords($product['product_name']) ?></a>
                                    <br/>
                                </td>
                                <td class="text-left">
                                    <?= ucwords($product['quantity']) ?>
                                </td>
                                <td class="text-right"><?php
                                    echo number_format(Yii::$app->checkoutFunctions->getProductPrice($product['sell_price'], $product['discount'])) . " " . Yii::t('app', 'VNDLabel');
                                   ?>
                                </td>
                                <td class="text-right"><?php
                                    echo number_format($product['total_price']) . " " . Yii::t('app', 'VNDLabel');
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row ">
                    <div class="col-sm-6 col-sm-offset-6">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td class="text-right"><strong><?= Yii::t('app', 'CheckoutResult SumPriceLabel') ?></strong></td>
                                <td class="text-right"><?= number_format($total_price) . " " . Yii::t('app', 'VNDLabel') ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong><?= Yii::t('app', 'CheckoutResult VoucherLabel') ?></strong></td>
                                <td class="text-right"><?php if(!empty($voucher)){ echo $voucher['code']."(".$voucher['discount']."%)";} else echo "Không"; ?></td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong><?= Yii::t('app', 'CheckoutResult PayPriceLabel') ?></strong></td>
                                <td class="text-right"><?= number_format($total_price - $discount_price ) . " " . Yii::t('app', 'VNDLabel') ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
            <?php endif?>
            <div class="buttons">
                <div class="pull-right"><a
                        href="<?= $baseUrl ?>"
                        class="btn btn-default"><?= Yii::t('app', 'ContinueShoppingLabel') ?></a></div>
            </div>

        </div>
    </div>
</div>



