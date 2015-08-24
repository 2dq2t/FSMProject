<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = Yii::t('app', 'Invoice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="metronic/assets/pages/css/invoice.css" rel="stylesheet" type="text/css"/>

<?php
$count = 0;
foreach ($models as $model) { ?>
    <?php
    $order_details_extend = \backend\models\OrderDetailsExtend::find()->where(['order_id' => $model["order_view"]->order_id])->all();
    $total_tax = 0;
    $total_discount = 0;
    $total_before_tax = 0;
    $total_after_tax = 0;
    ?>
    <!-- BEGIN PAGE CONTENT-->
    <div class="portlet light" id="order_id_<?= $count ?>">
        <div class="portlet-body">
            <div class="invoice">
                <div class="row invoice-logo">
                    <div class="col-xs-6 invoice-logo-space">
                        <img
                            src="../../frontend/web/images/data/logo2.png" class="img-responsive" width="300" height="350" alt=""/>
                    </div>
                    <div class="col-xs-6">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-4">
                        <h3><?= Yii::t('app', 'Shipping address') ?></h3>
                        <ul class="list-unstyled">
                            <li>
                                <?php echo $model['order_view']->full_name; ?>
                            </li>
                            <li>
                                <?= $model['order_view']->address ?>
                            </li>
                            <li>
                                <?= '0' . number_format($model['order_view']->phone_number, 0, '', ' ') ?>
                            </li>
                            <li>
                                <?= $model['order_view']->email ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-4">
                    </div>
                    <div class="col-xs-4 invoice-payment">
                        <h3><?=Yii::t('app', 'Invoice Details')?></h3>
                        <ul class="list-unstyled">
                            <li>
                                <strong><?= Yii::t('app', 'Order')?>#</strong> <?= $model['order_view']->order_id ?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Order Date')?>:</strong> <?= date('M d Y', $model['order_view']->order_date) ?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Receiving Date')?>:</strong> <?= date('M d Y', $model['order_view']->receiving_date) ?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Order Status')?>:</strong> <?php
                                if ($model['order_view']->order_status_id === 1) {
                                    $label_class = 'label-info';
                                    $value = \backend\models\OrderStatus::find()->where(['id' => $model['order_view']->order_status_id])->one()['name'];
                                } else if ($model['order_view']->order_status_id == 2) {
                                    $label_class = 'label-primary';
                                    $value = \backend\models\OrderStatus::find()->where(['id' => $model['order_view']->order_status_id])->one()['name'];
                                } else if ($model['order_view']->order_status_id == 3) {
                                    $label_class = 'label-danger';
                                    $value = \backend\models\OrderStatus::find()->where(['id' => $model['order_view']->order_status_id])->one()['name'];
                                } else {
                                    $label_class = 'label-success';
                                    $value = \backend\models\OrderStatus::find()->where(['id' => $model['order_view']->order_status_id])->one()['name'];
                                }

                                echo \yii\helpers\Html::tag(
                                    'span',
                                    Yii::t('app', $value)
//                                    ,['class' => "label $label_class"]
                                );

                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th><?= Yii::t('app', 'Product name') ?></th>
                                <th><?= Yii::t('app', 'Tax percent') ?></th>
                                <th class="text-right"><?= Yii::t('app', 'Price') ?></th>
                                <th class="text-right"><?= Yii::t('app', 'Quantity') ?></th>
                                <th class="text-right"><?= Yii::t('app', 'Tax amount') ?></th>
                                <th class="text-right"><?= Yii::t('app', 'Net amount') ?></th>
                                <th class="text-right"><?= Yii::t('app', 'Total (after tax)')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /** @var $order_details \backend\models\OrderDetailsExtend*/
                            foreach($model['order_details_extend'] as $order_details){
                                ?>
                                <tr>
                                    <td class="text-center count"></td>
                                    <td><?= $order_details->name?></td>
                                    <td><?= $order_details->tax . ' %'?></td>
                                    <td class="text-right"><?= number_format($order_details->sell_price,2) ?> </td>
                                    <td class="text-right"><?= $order_details->quantity ?></td>
                                    <td class="text-right"><?= number_format($order_details->quantity * $order_details->sell_price *(1- $order_details->discount/100) * $order_details->tax/100, 2); ?></td>
                                    <?php $total_tax += $order_details->quantity * $order_details->sell_price *(1- $order_details->discount/100)* $order_details->tax/100?>
                                    <td class="text-right"><?= number_format(($order_details->quantity * $order_details->sell_price * (1 - $order_details->discount/100) *(1 - $order_details->tax/100)), 2); ?></td>
                                    <?php $total_before_tax += $order_details->quantity * $order_details->sell_price * (1 - $order_details->discount/100) * (1 - $order_details->tax/100) ?>
                                    <td class="text-right"><?= number_format($order_details->sell_price * $order_details->quantity * (1 - $order_details->discount/100), 2)?></td>
                                    <?php $total_after_tax += $order_details->sell_price * $order_details->quantity * (1 - $order_details->discount / 100)?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="well">
                            <address>
                                <strong>FreshGarden, Inc.</strong><br/>
                                Km29 Hoa Lac Hightech, Thach Hoa<br/>
                                Thach That, Ha Noi, VietNam<br/>
                                <abbr title="Phone">P:</abbr> (0942) 145-1810
                            </address>
                            <address>
                                <strong>FreshGarden</strong><br/>
                                <a href="http://freshgardenhl.com.vn">
                                    http://freshgardenhl.com.vn </a>
                            </address>
                        </div>
                    </div>
                    <div class="col-xs-3"></div>
                    <div class="col-xs-5 invoice-block ">
                        <ul class="list-unstyled amounts">
                            <li>
                                <strong ><?= Yii::t('app', 'Shipping fee')?>:</strong><?= number_format($model['order_view']->shipping_fee, 2) ?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Net amount')?>:</strong> <?= number_format($total_before_tax, 2)?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Tax amount')?>:</strong> <?= number_format($total_tax,2) ?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Total (after tax)')?>:</strong> <?= number_format($total_after_tax,2) ?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Voucher discount')?>:</strong>
                                <?php $discount = $model['order_view']->voucher_discount != null ? $total_before_tax * $model['order_view']->voucher_discount / 100 : 0?>
                                <?= number_format($discount, 2) ?>
                            </li>
                            <li>
                                <strong><?= Yii::t('app', 'Total')?>:</strong> <?= number_format($total_after_tax + $model['order_view']->shipping_fee - $discount, 2) ?>
                            </li>
                        </ul>
                        <br/>
                        <a class="btn btn-md blue hidden-print margin-bottom-5 print"
                           onclick="printF('order_id_<?= $count ?>');">
                            <?= Yii::t('app', 'Print Invoice') ?> <i class="fa fa-print"></i>
                        </a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-4">
                        <h4><?= Yii::t('app', 'Buyer')?></h4>
                        <div class="center"></div>
                    </div>
                    <div class="col-xs-4"></div>
                    <div class="col-xs-4"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
<?php } $count++; ?>

<script type="text/javascript">

    var settings = {};

    function printF(id) {
        var writeDoc;
        var printWindow;

        printWindow = new Popup();
        writeDoc = printWindow.doc;
        writeDoc.open();
        writeDoc.write(docType() + "<html>" + getHead("Invoice") + getBody(id) + "</html>");
        writeDoc.close();

        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    function docType() {
        return '<!DOCTYPE HTML>';
    }

    function getHead(title) {
        var head = "<head><title>" + title + "</title>\n";
        $(document).find("link")
            .filter(function () {
                head += '<link type="text/css" rel="stylesheet" href="' + $(this).attr("href") + '" >\n';
                return $(this).attr("rel").toLowerCase() == "stylesheet";
            });
        head += "</head>";
        return head;
    }

    function getBody(printElement) {
        var printcontent = document.getElementById(printElement);
        return '<body>' +
            '<div class="page-container">\n' +
            '<div class="page-content">\n' +
            '<div class="container">\n' +
            '<div class="' + $(printcontent).attr("class") + '">' + $(printcontent).html() + '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</body>\n';
    }

    function Popup() {
        var windowAttr = "location=yes,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no";
        windowAttr += ",width=" + settings.popWd + ",height=" + settings.popHt;
        windowAttr += ",resizable=yes,screenX=" + settings.popX + ",screenY=" + settings.popY + ",personalbar=no,scrollbars=yes";

        var newWin = window.open("", "_blank", windowAttr);

        newWin.doc = newWin.document;

        return newWin;
    }
</script>


