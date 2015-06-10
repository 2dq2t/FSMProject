<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $guest common\models\Guest */
/* @var $address common\models\Address */
/* @var $city common\models\City */
/* @var $district common\models\District */
/* @var $product common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo lavrentiev\yii2toastr\Toastr::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'success',
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'message' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
            'clear' => false,
            'options' => [
                "closeButton" => true,
                "positionClass" => "toast-top-right",
                "timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<!--<div class="form-group no-margin">-->

<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['order/index'], ['class' => 'btn btn-default btn-circle']) ?>

<?php if ($model->isNewRecord): ?>
    <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>
<?php endif; ?>

<?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') : '<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

<!--</div>-->
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
        <!--        <form class="form-horizontal form-row-seperated" action="#">-->
        <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['class' => 'form-horizontal']]); ?>
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i><?= $this->title?>
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
                <!--                <form action="#" class="form-horizontal">-->
                <div class="form-body">
                    <h3 class="form-section"><?= Yii::t('app', 'Customer')?></h3>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($guest, 'full_name', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Eneter customer full name')]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($guest, 'email', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter customer email')]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($guest, 'phone_number',[
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->textInput(['maxlength' => 15 , 'placeholder' => Yii::t('app', 'Enter customer phone number')]) ?>
                        </div>
                    </div>
                    <h3 class="form-section"><?= Yii::t('app', 'Shipping')?></h3>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'shipping_fee', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->textInput(['maxlength' => 100]) ?>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <?= $form->field($model, 'receiving_date', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->widget(\kartik\date\DatePicker::classname(), [
                                'options' => ['placeholder' => Yii::t('app', 'Enter receiving date ..')],
                                'removeButton' => false,
                                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'startDate' => '-0d',
                                    'todayHighlight' => true
                                ]
                            ]) ?>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($address, 'detail', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->textInput(['maxlength' => 100])->label("Address") ?>
                        </div>
                        <div class="col-md-6">

                            <?php
                            if ($city->id) {
                                echo $form->field($city, 'id', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                    [
                                        'prompt'=>Yii::t('app', 'Select City'),
                                        'onchange'=>'
                                                            $.post( "index.php?r=order/getdistrict&id="+$(this).val(), function( file ) {
                                                                $( "select#district-id" ).length = 0;
                                                                $( "select#district-id" ).html( file );
                                                        });'
                                    ]);
                            } else {
                                echo $form->field($city, 'id', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                    ['prompt'=>Yii::t('app', 'Select City')]);
                            }

                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($city->id) {
                                echo $form->field($address, 'district_id', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->dropDownList(
                                    \yii\helpers\ArrayHelper::map(
                                        \common\models\District::find()
                                            ->where(['city_id' => $city->id])
                                            ->all(), 'id', 'name'),
                                    [
                                        'prompt'=>Yii::t('app', 'Select District'),
                                    ]
                                );
                            } else {
                                echo $form->field($address, 'district_id', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->widget(\kartik\widgets\DepDrop::classname(), [
                                    'options'=>['prompt' => Yii::t('app', 'Select District')],
                                    'pluginOptions'=>[
                                        'depends'=>[Html::getInputId($city, 'id')],
                                        'placeholder'=>Yii::t('app', 'Select District'),
                                        'url'=>\yii\helpers\Url::to(['/order/getdistrict'])
                                    ]
                                ]);
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <h3 class="form-section"><?= Yii::t('app', 'Order Info')?></h3>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'order_date', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->widget(\kartik\date\DatePicker::classname(), [
                                'options' => ['placeholder' => Yii::t('app', 'Enter order date ..')],
                                'removeButton' => false,
                                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'startDate' => '-0d',
                                    'todayHighlight' => true
                                ]
                            ]) ?>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <?= $form->field($model, 'order_status_id', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->dropDownList(
                                \yii\helpers\ArrayHelper::map(\backend\models\OrderStatus::find()->all(), 'id', 'name'),
                                ['prompt'=>Yii::t('app', 'Select Order Status')]
                            ) ?>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'description', [
                                'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                            ])->textarea(['rows' => 3]) ?>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">

                        </div>
                        <!--/span-->
                    </div>
                    <h3 class="form-section"><?= Yii::t('app', 'Product')?></h3>
                    <!--/row-->
                    <script type="text/javascript">
                        var products_added = [];

                        var old_value = -1;
                        function setOldValue(id) {
                            old_value = id.find('option:selected').val();
                        }

                        function getProductData(id) {
                            var select_id = id.attr('id');
                            var i=select_id.substring(select_id.indexOf("-")+1,select_id.lastIndexOf("-"));

                            var selected_value = parseInt(id.find('option:selected').val());

                            // if select change value
//                            if(selected_value != old_value) {
//                                console.log("o1: " + old_value);
//                                console.log("sle: " + selected_value);
//                                console.log(products_added.indexOf(selected_value));
//                                if (products_added.indexOf(selected_value) != -1) {
//                                    console.log("dasdsa");
//                                    var index = products_added.indexOf(selected_value);
//                                    products_added.splice(index, 1);
//                                }
//
//                                console.log(products_added);
//
//                                clear(i);
//                            }

                            // if has a selected value in products added array
//                            if ($.inArray(selected_value, products_added) !== -1) {
//                                clear(i);
//                                alert("You already select this product. Please again!");
//                                return;
//                            }
//
//                            if (selected_value) {
//                                // if not add value to products_added array
//                                products_added.push(selected_value);
//                            }

                            if(selected_value) {
                                $.post( "index.php?r=order/get-product-info&id=" + selected_value, function( product_info ) {
                                    var product_infos = JSON.parse(product_info);
                                    $("#product-" + i +"-price").text(product_infos["price"]);
                                    $("#product-" + i +"-unit").text(product_infos["unit"]);
                                    $("#product-" + i +"-total").text(product_infos["price"] * document.getElementById("orderdetails-" + i + "-quantity").value);
                                    var image = document.createElement("img");
                                    var imageParent = document.getElementById("product-" + i +"-image");
                                    image.className = "img-thumbnail";
                                    image.src = "../../frontend/web/" + product_infos["image"];
                                    imageParent.appendChild(image);
                                });
                            } else {
                                var index = products_added.indexOf(selected_value);
                                if (index > -1) {
                                    products_added.splice(index, 1);
                                }
                            }
                        }

                        function clear(id) {
                            var select_id = id.attr('id');
                            var i=select_id.substring(select_id.indexOf("-")+1,select_id.lastIndexOf("-"));
                            id.select2('val', '');
                            $("#product-" + i +"-image").text("");
                            $("#product-" + i +"-price").text("");
                            $("#product-" + i +"-unit").text("");
                            $("#product-" + i +"-total").text("");
                            $("#orderdetails-" + i + "-quantity").val(1);
                        }
                    </script>
                    <?php
                    $script = "
                            jQuery('.dynamicform_wrapper').on('afterInsert', function(e, item) {
                                var select_id = $(item).find('select').attr('id');
                                $(item).find('select').value = '';
                                clear($(item).find('select'));
                                $('#' + select_id).on('select2:selecting', function() {
                                    setOldValue($(this));
                                });

                                $('#' + select_id).change(function(){
                                    getProductData($(item).find('select'));
                                });
                            });
                        ";
                    $this->registerJs($script);
                    ?>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <?php DynamicFormWidget::begin([
                                'widgetContainer' => 'dynamicform_wrapper',
                                'widgetBody' => '.container-items',
                                'widgetItem' => '.item',
                                'limit' => 8,
                                'min' => 1,
                                'insertButton' => '.add-item',
                                'deleteButton' => '.remove-item',
                                'model' => $order_details[0],
                                'formId' => 'dynamic-form',
                                'formFields' => [
                                    'product_id',
                                    'order_id',
                                    'quantity',
                                    'sell_price'
                                ]
                            ]); ?>

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
                                    <th width="6%" tabindex="0" rowspan="1" colspan="1">
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="container-items">

                                <?php foreach ($order_details as $i => $order_item): ?>

                                    <?php
                                    // necessary for update action.
                                    if (! $order_item->isNewRecord) {
                                        echo Html::activeHiddenInput($order_item, "[{$i}]sell_price");
                                        echo Html::activeHiddenInput($order_item, "[{$i}]order_id");
                                    }
                                    ?>
                                    <tr role="row" class="item even">
                                        <td class="count"></td>
                                        <td>
                                            <div class="col-md-12">
                                                <?php echo $form->field($order_item, "[{$i}]product_id")->widget(\kartik\widgets\Select2::className(), [
                                                    'data' => \yii\helpers\ArrayHelper::map(\common\models\Product::find()->where(['active' => \common\models\Product::STATUS_ACTIVE])->all(), 'id', 'name'),
                                                    'options' => ['placeholder' => 'Select a state ...'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'pluginEvents' => [
                                                        "change" => "function() {
                                                            getProductData($(this));
                                                        }",
                                                        "select2:selecting" => "function() {
                                                            setOldValue($(this));
                                                        }",
                                                    ]
                                                ])->label(false)?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($order_item->product_image) { ?>
                                                <span id="product-<?php echo "{$i}";?>-image" class="product-image">
                                                            <img src="<?php echo "../../frontend/web/" . $order_item->product_image?>" alt="" class="img-thumbnail" />
                                                        </span>
                                            <?php } else { ?>
                                                <span id="product-<?php echo "{$i}";?>-image" class="product-image"></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($order_item->product_unit) { ?>
                                                <span id="product-<?php echo "{$i}";?>-unit" class="product-unit"><?php echo $order_item->product_unit?></span>
                                            <?php } else { ?>
                                                <span id="product-<?php echo "{$i}";?>-unit" class="product-unit"></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($order_item->sell_price) { ?>
                                                <span id="product-<?php echo "{$i}";?>-price" class="product-price"><?php echo $order_item->sell_price?></span>
                                            <?php } else { ?>
                                                <span id="product-<?php echo "{$i}";?>-price" class="product-price"></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?= $form->field($order_item, "[{$i}]quantity", [
                                                'template' => "<div class='col-md-12'>{input}{error}</div>"
                                            ])->widget(\kartik\touchspin\TouchSpin::className(), [
                                                'options' => [
                                                    'onchange' => '
                                                                var i=id.substring(id.indexOf("-")+1,id.lastIndexOf("-"));
                                                                document.getElementById("product-" + i + "-total").innerHTML =
                                                                    (document.getElementById("orderdetails-" + i + "-quantity").value * document.getElementById("product-" + i + "-price").innerHTML)
                                                            ',
                                                ],
                                                'pluginOptions' => [
                                                    'initval' => 1,
                                                    'min' => 1,
                                                    'max' => 10000000,
                                                    'step' => 1,
                                                    'verticalbuttons' => true,
                                                    'verticalupclass' => 'glyphicon glyphicon-plus',
                                                    'verticaldownclass' => 'glyphicon glyphicon-minus',
                                                ],
                                            ])?>
                                        </td>
                                        <td>
                                            <?php if ($order_item->product_total) { ?>
                                                <span id="product-<?php echo "{$i}";?>-total" class="product-total"><?php echo $order_item->product_total?></span>
                                            <?php } else { ?>
                                                <span id="product-<?php echo "{$i}";?>-total" class="product-total"></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                        </td>
                                    </tr>

                                <?php if ($order_item->product_id) {?>
                                    <script type="text/javascript">
                                        //                                                    document.getElementById("orderdetail-<?php //echo $i; ?>//-quantity").style.display = 'none';
                                        //document.getElementById("orderdetail-'. $i .'-quantity").parentNode.removeAttribute("style");
                                        products_added.push("<?php echo $order_item->product_id ?>");
                                    </script>
                                <?php }?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php DynamicFormWidget::end(); ?>
                        </div>
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
                <!--                </form>-->
                <!-- END FORM-->
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
