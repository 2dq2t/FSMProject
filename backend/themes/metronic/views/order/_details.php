<?php
/**
 * Created by PhpStorm.
 * User: Dung
 * Date: 7/8/2015
 * Time: 1:16 AM
 */

use kartik\detail\DetailView;

/* @var $model backend\models\OrderView */
/* @var $order_details_extend backend\models\OrderDetailsExtend */
/* @var $order_details common\models\OrderDetails */

$order_details_extend = \backend\models\OrderDetailsExtend::find()->where(['order_id' => $model->order_id])->all();
$total_tax = 0;
$total_discount = 0;
$total_before_tax = 0;
$total_after_tax = 0;
//var_dump($order);
?>
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-basket font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">
								<?= Yii::t('app', 'Order') . ' #' . $model->order_id ?> </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet grey-cascade box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-cogs"></i><?= Yii::t('app', 'Products Ordered')?>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th><?= Yii::t('app', 'Product name')?></th>
                                                        <th><?= Yii::t('app', 'Tax percent')?></th>
                                                        <th class="text-right"><?= Yii::t('app', 'Price')?></th>
                                                        <th class="text-right"><?= Yii::t('app', 'Quantity')?></th>
                                                        <th class="text-right"><?= Yii::t('app', 'Tax amount')?></th>
                                                        <th class="text-right"><?= Yii::t('app', 'Net amount')?></th>
                                                        <th class="text-right"><?= Yii::t('app', 'Total (after tax)')?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    /** @var $order_details \backend\models\OrderDetailsExtend*/
                                                    foreach($order_details_extend as $order_details){
                                                        ?>
                                                        <tr>
                                                            <td class="text-center count"></td>
                                                            <td><?= $order_details->name?></td>
                                                            <td><?= $order_details->tax . ' %'?></td>
                                                            <td class="text-right"><?= number_format($order_details->sell_price,2) ?> </td>
                                                            <td class="text-right"><?= $order_details->quantity ?></td>
                                                            <td class="text-right"><?= number_format($order_details->quantity * $order_details->sell_price * (1 - $order_details->discount/100) * $order_details->tax/100, 2); ?></td>
                                                            <?php $total_tax += $order_details->quantity * $order_details->sell_price * (1 - $order_details->discount/100) * $order_details->tax/100?>
                                                            <td class="text-right"><?= number_format($order_details->quantity * $order_details->sell_price * (1 - $order_details->discount/100) * (1 - $order_details->tax/100), 2); ?></td>
                                                            <?php $total_before_tax += $order_details->quantity * $order_details->sell_price * (1 - $order_details->discount/100) * (1 - $order_details->tax/100) ?>
                                                            <td class="text-right"><?= number_format($order_details->sell_price * $order_details->quantity * (1 - $order_details->discount/100), 2)?></td>
                                                            <?php $total_after_tax += $order_details->sell_price * $order_details->quantity * (1 - $order_details->discount/100)?>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <div class="well">
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-8 name">
                                                <?= Yii::t('app', 'Shipping fee')?>:
                                            </div>
                                            <div class="col-md-3 value">
                                                <?= number_format($model->shipping_fee, 2) ?>
                                            </div>
                                        </div>
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-8 name">
                                                <?= Yii::t('app', 'Net amount')?>:
                                            </div>
                                            <div class="col-md-3 value">
                                                <?= number_format($total_before_tax, 2)?>
                                            </div>
                                        </div>
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-8 name">
                                                <?= Yii::t('app', 'Tax amount')?>:
                                            </div>
                                            <div class="col-md-3 value">
                                                <?= number_format($total_tax,2) ?>
                                            </div>
                                        </div>
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-8 name">
                                                <?= Yii::t('app', 'Total (after tax)')?>:
                                            </div>
                                            <div class="col-md-3 value">
                                                <?= number_format($total_after_tax,2) ?>
                                            </div>
                                        </div>
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-8 name">
                                                <?= Yii::t('app', 'Voucher discount')?>:
                                            </div>
                                            <div class="col-md-3 value">
                                                <?php $discount = $model->voucher_discount != null ? $total_before_tax * $model->voucher_discount / 100 : 0?>
                                                <?= number_format($discount, 2) ?>
                                            </div>
                                        </div>
                                        <div class="row static-info align-reverse">
                                            <div class="col-md-8 name">
                                                <?= Yii::t('app', 'Total')?>:
                                            </div>
                                            <div class="col-md-3 value">
                                                <?= number_format($total_after_tax + $model->shipping_fee - $discount, 2) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <?php
                    $attributes = [
                        [
                            'attribute'=>'description',
                            'format'=>'raw',
                            'value'=>'<span class="text-justify"><em>' . $model->description . '</em></span>',
                            'type'=>DetailView::INPUT_TEXTAREA,
                            'options'=>['rows'=>4]
                        ],
                    ];
                    echo DetailView::widget([
                        'model'=>$model,
                        'condensed'=>true,
                        'hover'=>true,
                        'mode'=>DetailView::MODE_VIEW,
                        'fadeDelay' => 100,
                        'buttons1' => '{update}',
                        'buttons2' => '{view} {save}',
                        'formOptions' => [
                            'action' => \yii\helpers\Url::toRoute(['order/update-description', 'order_id' => $model->order_id])
                        ],
                        'panel'=>[
                            'heading'=> Yii::t('app', 'Description'),
                            'type'=>DetailView::TYPE_INFO,
                        ],
                        'attributes' => $attributes,
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>