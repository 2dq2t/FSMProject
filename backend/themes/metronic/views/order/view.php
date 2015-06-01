<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $customer common\models\Guest */
/* @var $address common\models\Address */
/* @var $district common\models\District */
/* @var $city common\models\City */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginBlock('submit'); ?>
<!--<div class="form-group no-margin">-->

<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['order/index'], ['class' => 'btn btn-default btn-circle']) ?>

<?= Html::a('<i class="fa fa-pencil"></i> ' . Yii::t('app', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

<!--</div>-->
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i><?= Yii::t('app', 'Order')?>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="reload" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form class="form-horizontal" role="form">
                    <div class="form-body">
                        <h2 class="margin-bottom-20"> <?= Yii::t('app', 'View Order Info - No: '. $this->title)?> </h2>
                        <h3 class="form-section"><?= Yii::t('app', 'Customer ')?></h3>
                        <?php
                        $customer = \common\models\Guest::find()->where(['id' => $model->guest_id])->one();
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Full Name: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $customer->full_name ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Email: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $customer->email?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Phone Number: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $customer->phone_number?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <h3 class="form-section"><?= Yii::t('app', 'Shipping Info ')?></h3>
                        <?php
                        $address = \common\models\Address::find()->where(['id' => $model->address_id])->one();
                        $district = \common\models\District::find()->where(['id' => $address->district_id])->one();
                        $city = \common\models\City::find()->where(['id' => $district->city_id])->one();
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Shipping fee: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $model->shipping_fee ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Address: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $address->detail . ', ' . $district->name . ', ' . $city->name ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <h3 class="form-section">Order Info</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Order Id: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $model->id ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Order Date: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= date('m/d/Y', $model->order_date)?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Receiving date: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= date('m/d/Y', $model->receiving_date)?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Shipping fee: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $model->shipping_fee ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Tax amount: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $model->tax_amount ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= Yii::t('app', 'Description: ')?></label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            <?= $model->description ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <h3 class="form-section"><?= Yii::t('app', 'Products Info ')?></h3>
                        <?php

                        $order_details = \common\models\OrderDetails::find()->where(['order_id' => $model->id])->all();

                        $products_info = [];

                        foreach($order_details as $i => $order_detail){
                            $products_info[$i]['product_image'] = \common\models\Image::find()->select('path')->where(['product_id' => $order_detail['product_id']])->one()['path'];
                            $products_info[$i]['product_name'] = \common\models\Product::find()->select('name')->where(['id' => $order_detail['product_id']])->one()['name'];
                            $unit_id = \common\models\Product::find()->where(['id' => $order_detail['product_id']])->one()['unit_id'];
                            $products_info[$i]['product_unit'] = \common\models\Unit::find()->select('name')->where(['active' => 1, 'id' => $unit_id])->one()['name'];
                            $products_info[$i]['sell_price'] = $order_detail['sell_price'];
                            $products_info[$i]['product_quantity'] = $order_detail['quantity'];
                            $products_info[$i]['product_total'] = $order_detail['quantity'] * $order_detail['sell_price'];
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-striped table-bordered table-hover no-footer" id="datatable_reviews" role="grid">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="3%" tabindex="0" rowspan="1" colspan="1">
                                            #
                                        </th>
                                        <th width="20%" tabindex="0" rowspan="1" colspan="1">
                                            Product name
                                        </th>
                                        <th width="10%" tabindex="0" rowspan="1" colspan="1">
                                            Image
                                        </th>
                                        <th width="5%" tabindex="0" rowspan="1" colspan="1">
                                            Unit
                                        </th>
                                        <th width="10%" tabindex="0" rowspan="1" colspan="1">
                                            Price
                                        </th>
                                        <th width="15%" tabindex="0" rowspan="1" colspan="1">
                                            Quantity
                                        </th>
                                        <th width="10%" tabindex="0" rowspan="1" colspan="1">
                                            Total
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="container-items">

                                    <?php foreach ($products_info as $i => $product_info): ?>
                                        <tr role="row" class="item even">
                                            <td class="count"></td>
                                            <td>
                                                <?php if ($product_info['product_name']) { ?>
                                                    <span id="product-<?php echo "{$i}";?>-image" class="product-image">
                                                        <?= $product_info['product_name']?>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($product_info['product_image']) { ?>
                                                    <span id="product-image" class="product-image">
                                                        <img src="<?php echo "../../frontend/web/" . $product_info['product_image']?>" alt="" class="img-thumbnail"  width="50%" height="50%" />
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($product_info['product_unit']) { ?>
                                                    <span id="product-unit" class="product-unit"><?php echo $product_info['product_unit']?></span>
                                                <?php }  ?>
                                            </td>
                                            <td>
                                                <?php if ($product_info['sell_price']) { ?>
                                                    <span id="product-price" class="product-price"><?php echo $product_info['sell_price']?></span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($product_info['product_quantity']) { ?>
                                                    <span id="product-total" class="product-quantity"><?php echo $product_info['product_quantity']?></span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($product_info['product_total']) { ?>
                                                    <span id="product-total" class="product-total"><?php echo $product_info['product_total']?></span>
                                                <?php } ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!--/span-->
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-12 pull-right">
                                        <?php echo $this->blocks['submit']?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
