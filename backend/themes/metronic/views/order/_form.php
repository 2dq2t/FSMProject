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
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay' => 3000
        ]);
    }
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<!--<div class="form-group no-margin">-->

    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['order/index'], ['class' => 'btn btn-default btn-circle']) ?>

    <?= Html::button('<i class="fa fa-reply"></i> '. Yii::t('app', 'Reset'), ['class' => 'btn btn-default btn-circle', 'type' => 'reset']) ?>

    <?php if ($model->isNewRecord): ?>
        <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>
    <?php endif; ?>

    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') : '<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

<!--</div>-->
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
<!--        <form class="form-horizontal form-row-seperated" action="#">-->
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['class' => 'form-horizontal form-row-seperated']]); ?>
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-basket font-green-sharp"></i>
									<span class="caption-subject font-green-sharp bold uppercase">
									<?php echo Yii::t('app', $this->title)?> </span>
<!--                        <span class="caption-helper">Man Tops</span>-->
                    </div>
                    <div class="actions btn-set">
                        <?php echo $this->blocks['submit'] ;?>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_order" data-toggle="tab" aria-expanded="true">
                                    <?php echo Yii::t('app', 'Order Detail')?></a>
                            </li>
                            <li class="">
                                <a href="#tab_customer" data-toggle="tab" aria-expanded="false">
                                    <?php echo Yii::t('app', 'Customer Details')?></a>
                            </li>
                            <li class="">
                                <a href="#tab_shipping" data-toggle="tab" aria-expanded="false">
                                    <?php echo Yii::t('app', 'Shipping Info')?></a>
                            </li>
                            <li class="">
                                <a href="#tab_product" data-toggle="tab" aria-expanded="false">
                                    <?php echo Yii::t('app', 'Products')?></a>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content no-space">
                            <!-- BEGIN TAB ORDER -->
                            <div class="tab-pane" id="tab_order">
                                <div class="form-body">
                                    <div class="form-group">
                                        <?= $form->field($model, 'description', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->textarea(['rows' => 3]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($model, 'order_date', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->widget(\kartik\date\DatePicker::classname(), [
                                            'options' => ['placeholder' => Yii::t('app', 'Enter order date ..')],
                                            'removeButton' => false,
                                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                            'language' => 'vi',
                                            'pluginOptions' => [
                                                'autoclose'=>true,
                                                'setDate' => date('yyyy-mm-dd'),
                                                'format' => 'yyyy-mm-dd',
                                                'todayHighlight' => true
                                            ]
                                        ]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($model, 'discount', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->textInput() ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($model, 'tax_amount', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->textInput() ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($model, 'net_amount', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->textInput() ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($model, 'voucher_id', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->dropDownList(
                                            \yii\helpers\ArrayHelper::map(\common\models\Voucher::find()->all(), 'id', 'name'),
                                            ['prompt'=>Yii::t('app', 'Select Voucher')]
                                        ) ?>
                                    </div>
                                    <div class="form-group last">
                                        <?= $form->field($model, 'order_status_id', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->dropDownList(
                                            \yii\helpers\ArrayHelper::map(\backend\models\OrderStatus::find()->all(), 'id', 'name'),
                                            ['prompt'=>Yii::t('app', 'Select Order Status')]
                                        ) ?>
                                    </div>
                                </div>
                            </div>
                            <!-- END TAB ORDER -->
                            <!-- BEGIN TAB CUSTOMER -->
                            <div class="tab-pane" id="tab_customer">
                                <div class="form-body">
                                    <div class="form-group">
                                        <?= $form->field($guest, 'full_name', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Eneter customer fullname')]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($guest, 'email', [
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter customer email')]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($guest, 'phone_number',[
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->textInput(['maxlength' => 15 , 'placeholder' => Yii::t('app', 'Enter customer phone number')]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= $form->field($guest, 'customer_id' ,[
                                            'template' => "<label class='control-label col-md-2'>{label}</label><div class='col-md-10'>{input}{error}</div>"
                                        ])->dropDownList(
                                            \yii\helpers\ArrayHelper::map(\common\models\Customer::find()->all(), 'id', 'username'),
                                            ['prompt'=>Yii::t('app', 'Select user')]
                                        ) ?>
                                    </div>
                                </div>
                            </div>
                            <!-- END TAB CUSTOMER -->
                            <!-- BEGIN TAB SHIPPING -->
                            <div class="tab-pane" id="tab_shipping">
                                <div class="form-group">
                                    <?= $form->field($address, 'detail', [
                                        'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-4'>{input}{error}</div>"
                                    ])->textInput(['maxlength' => 100]) ?>
                                </div>
                                <div class="form-group">
                                    <?php
                                    if ($city->id) {
                                        echo $form->field($city, 'id', [
                                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-4'>{input}{error}</div>"
                                        ])->dropDownList(
                                            \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                            [
                                                'prompt'=>Yii::t('app', 'Select City'),
                                                'onchange'=>'
                                                            $.post( "index.php?r=staff/getdistrict&id="+$(this).val(), function( file ) {
                                                                $( "select#district-id" ).length = 0;
                                                                $( "select#district-id" ).html( file );
                                                                var event = new Event("change");
                                                                document.getElementById("district-id").dispatchEvent(event);
                                                        });'
                                            ]);
                                    } else {
                                        echo $form->field($city, 'id', [
                                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-4'>{input}{error}</div>"
                                        ])->dropDownList(
                                            \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                            ['prompt'=>Yii::t('app', 'Select City')]);
                                    }

                                    ?>
                                </div>
                                <div class="form-group">
                                    <?php
                                    if ($city->id) {
                                        echo $form->field($district, 'id', [
                                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-4'>{input}{error}</div>"
                                        ])->dropDownList(
                                            \yii\helpers\ArrayHelper::map(
                                                \common\models\District::find()
                                                    ->where(['city_id' => $city->id])
                                                    ->all(), 'id', 'name'),
                                            [
                                                'prompt'=>Yii::t('app', 'Select District'),
                                                'onchange'=>'
                                                    $.post( "index.php?r=staff/getward&id="+$(this).val(), function( file ) {
                                                        $( "select#address-ward_id" ).length = 0;
                                                        $( "select#address-ward_id" ).html( file );
                                                        var event = new Event("change");
                                                        document.getElementById("address-ward_id").dispatchEvent(event);
                                                    });'
                                            ]
                                        );
                                    } else {
                                        echo $form->field($district, 'name', [
                                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-4'>{input}{error}</div>"
                                        ])->widget(\kartik\widgets\DepDrop::classname(), [
                                            'options'=>['prompt' => Yii::t('app', 'Select District')],
                                            'pluginOptions'=>[
                                                'depends'=>[Html::getInputId($city, 'id')],
                                                'placeholder'=>Yii::t('app', 'Select District'),
                                                'url'=>\yii\helpers\Url::to(['/staff/getdistrict'])
                                            ]
                                        ]);
                                    }

                                    ?>
                                </div>
                                <div class="form-group">

                                    <?php

                                    if ($district->id) {
                                        echo $form->field($address, 'ward_id', [
                                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-4'>{input}{error}</div>"
                                        ])->dropDownList(
                                            \yii\helpers\ArrayHelper::map(
                                                \common\models\Ward::find()
                                                    ->where(['district_id' => $district->id])
                                                    ->all(), 'id', 'name'),
                                            ['prompt'=>Yii::t('app', 'Select Ward')]
                                        );
                                    } else {
                                        echo $form->field($address, 'ward_id', [
                                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-4'>{input}{error}</div>"
                                        ])->widget(\kartik\widgets\DepDrop::classname(), [
                                            'options'=>['prompt' => Yii::t('app', 'Select Ward')],
                                            'pluginOptions'=>[
                                                'depends'=>[
                                                    Html::getInputId($city, 'id'),
                                                    Html::getInputId($district, 'name')
                                                ],
                                                'placeholder'=>Yii::t('app', 'Select Ward'),
                                                'url'=>\yii\helpers\Url::to(['/staff/getward'])
                                            ]
                                        ]);
                                    }

                                    ?>

                                </div>
                            </div>
                            <!-- END TAB SHIPPING -->
                            <!-- BEGIN TAB PRODUCT -->
                            <div class="tab-pane active" id="tab_product">
<!--                                <div class="panel-body">-->
                            <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'dynamicform_wrapper',
                                    'widgetBody' => '.container-items',
                                    'widgetItem' => '.item',
                                    'limit' => 8,
                                    'min' => 1,
                                    'insertButton' => '.add-item',
                                    'deleteButton' => '.remove-item',
                                    'model' => $product[0],
                                    'formId' => 'dynamic-form',
                                    'formFields' => [
                                        'name',
                                        'price',
                                        'quantity',
                                    ],
                                ]); ?>
                                <div class="table-container">
                                    <div id="datatable_reviews_wrapper" class="dataTables_wrapper dataTables_extended_wrapper no-footer">
                                        <div class="row">
                                            <div class="dataTables_length" id="datatable_reviews_length">
											
                                                <div class="table-scrollable">
                                                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="datatable_reviews" aria-describedby="datatable_reviews_info" role="grid">
                                                        <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="3%" class="sorting_asc" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#
                                                            : activate to sort column ascending">
                                                                #
                                                            </th>
                                                            <th width="20%" class="sorting" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1" aria-label="Product name: activate to sort column ascending">
                                                                Product name
                                                            </th>
                                                            <th width="10%" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1">
                                                                Image
                                                            </th>
                                                            <th width="5%" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1">
                                                                Unit
                                                            </th>
                                                            <th width="10%" class="sorting" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending">
                                                                Price
                                                            </th>
                                                            <th width="10%" class="sorting" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1" aria-label="Quantity: activate to sort column ascending">
                                                                Quantity
                                                            </th>
                                                            <th width="10%" class="sorting" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1" aria-label="Total: activate to sort column ascending">
                                                                Total
                                                            </th>
                                                            <th width="6%" class="sorting" tabindex="0" aria-controls="datatable_reviews" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="container-items">
                                                        <?php foreach ($product as $i => $product_item): ?>
                                                            <tr role="row" class="item even">
                                                                <td class="count"></td>
                                                                <td>
                                                                    <div class="col-md-12">
                                                                        <?= $form->field($product_item, "[{$i}]name")->dropDownList(
                                                                            \yii\helpers\ArrayHelper::map(\common\models\Product::find()->all(), 'id', 'name'),
                                                                            [
                                                                                'prompt'=>Yii::t('app', 'Select product')
                                                                            ]
                                                                        )->label(false) ?>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <?= Html::activeLabel($product_item, "[{$i}]price", [
                                                                        'label' => '',
                                                                        'class'=>'col-sm-2'
                                                                    ])?>
                                                                </td>
                                                                <td><span class="label label-sm label-info">Pending</span></td>
                                                                <td><span class="label label-sm label-info">Pending</span></td>
                                                                <td>
                                                                    <?php
                                                                    echo \kartik\widgets\TouchSpin::widget([
                                                                        'name' => 'quantity',
                                                                        'pluginOptions' => [
                                                                            'initval' => 1,
                                                                            'min' => 1,
                                                                            'max' => 1000,
                                                                            'step' => 1,
                                                                            'buttonup_txt' => '+',
                                                                            'buttondown_txt' => '-'
                                                                        ],
                                                                    ]);
                                                                    ?>
                                                                </td>
                                                                <td><span class="label label-sm label-info">Pending</span></td>
                                                                <td>
                                                                    <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php DynamicFormWidget::end(); ?>
                            </div>
                            <!-- END TAB PRODUCT -->
                        </div>
                    </div>
                </div>
            </div>
<!--        </form>-->
        <?php ActiveForm::end(); ?>
    </div>
</div>
