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
//var_dump($order);
?>
<div class="skip-export kv-expanded-row" data-index="7" data-key="8" style="display: block;"><div class="kv-detail-content">
    <h3><?= Yii::t('app', 'Order') . ' #' . $model->order_id ?></h3>
    <div class="row">
<!--        <div class="col-sm-2">-->
<!--            <div class="img-thumbnail img-rounded text-center">-->
<!--                <img src="/images/book.jpg" style="padding:2px;width:100%">-->
<!--                <div class="small text-muted">Published: 2000-10-01</div>-->
<!--            </div>-->
<!--        </div>-->
        <div class="col-md-10">
            <table class="table table-bordered table-condensed table-hover">
                <tbody>
                    <tr class="success">
                        <th colspan="6" class="text-center text-success"><?= Yii::t('app', 'Products Ordered')?></th>
                    </tr>
                    <tr class="active">
                        <th class="text-center">#</th>
                        <th><?= Yii::t('app', 'Product name')?></th>
                        <th><?= Yii::t('app', 'Tax')?></th>
                        <th class="text-right"><?= Yii::t('app', 'Sell Price')?></th>
                        <th class="text-right"><?= Yii::t('app', 'Quantity')?></th>
                        <th class="text-right"><?= Yii::t('app', 'Total')?></th>
                    </tr>
                    <?php foreach($order_details_extend as $order_details){ ?>
                    <tr>
                        <td class="text-center count"></td>
                        <td><?= $order_details->name?></td>
                        <td><?= $order_details->tax . ' %'?></td>
                        <td class="text-right"><?= $order_details->sell_price ?> </td>
                        <td class="text-right"><?= $order_details->quantity ?></td>
                        <td class="text-right"><?= $order_details->discount > 0 ? $order_details->quantity * $order_details->sell_price * (1 - $order_details->discount) : $order_details->quantity * $order_details->sell_price ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
<!--        <div class="col-sm-1">-->
<!--            <div class="kv-button-stack">-->
<!--            <button type="button" class="btn btn-default btn-lg" title="" data-toggle="tooltip" data-original-title="Add to cart"><span class="glyphicon glyphicon-shopping-cart"></span></button>-->
<!--            <button type="button" class="btn btn-default btn-lg" title="" data-toggle="tooltip" data-original-title="Call for details"><span class="glyphicon glyphicon-earphone"></span></button>-->
<!--            <button type="button" class="btn btn-default btn-lg" title="" data-toggle="tooltip" data-original-title="Email for details"><span class="glyphicon glyphicon-envelope"></span></button>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
</div>