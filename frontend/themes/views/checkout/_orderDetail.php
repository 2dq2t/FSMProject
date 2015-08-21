<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 21/08/2015
 * Time: 3:09 CH
 */

use kartik\detail\DetailView;

/* @var $model backend\models\OrderView */
/* @var $order_details_extend backend\models\OrderDetailsExtend */
/* @var $order_details common\models\OrderDetails */

$order_details_extend = \backend\models\OrderDetailsExtend::find()->where(['order_id' => $model->order_id])->all();
$total_after_tax = 0;
//var_dump($order);
?>
<div class="row">
    <div class="col-sm-12 tab-content">
        <!-- Begin: life time stats -->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-basket font-green-sharp"></i>
								<?= Yii::t('app', 'Order') . ' #' . $model->order_id ?> </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-sm-12 col-sm-12">
                                    <div class="portlet grey-cascade box">
                                        <div class="portlet-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered shopping-cart">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th><?= Yii::t('app', 'CheckoutResult ProductName')?></th>
                                                        <th class="text-center"><?= Yii::t('app', 'CheckoutResult ProductPrice')?></th>
                                                        <th class="text-center"><?= Yii::t('app', 'CheckoutResult Quantity')?></th>
                                                        <th class="text-center"><?= Yii::t('app', 'CheckoutResult SumPrice')?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i =0;?>
                                                    <?php foreach($order_details_extend as $order_details){ $i++; ?>
                                                        <tr>
                                                            <td class="text-center count"><?=$i?></td>
                                                            <td><?= $order_details->name?></td>
                                                            <td class="text-center"><?= number_format($order_details->sell_price) ?> </td>
                                                            <td class="text-center"><?= number_format($order_details->quantity) ?></td>
                                                            <td class="text-center"><?= number_format($order_details->sell_price * $order_details->quantity)?></td>
                                                            <?php $total_after_tax += $order_details->sell_price * $order_details->quantity?>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-sm-6 col-sm-offset-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td class="text-right"><strong><?= Yii::t('app', 'CheckoutResult SumPriceLabel') ?></strong></td>
                                            <td class="text-right"><?= number_format($total_after_tax) . " " . Yii::t('app', 'VNDLabel') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong><?= Yii::t('app', 'CheckoutResult VoucherLabel') ?></strong></td>
                                            <td class="text-right"><?php if(!empty($voucher)){ echo $voucher['code']."(".$voucher['discount']."%)";} else echo "Không"; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong><?= Yii::t('app', 'CheckoutResult PayPriceLabel') ?></strong></td>
                                            <td class="text-right"><?= number_format(0 ) . " " . Yii::t('app', 'VNDLabel') ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>