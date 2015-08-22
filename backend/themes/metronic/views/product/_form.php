<?php

use yii\helpers\Html;
use kartik\alert\Alert;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$product_image = [];
$previewConfig = [];

if(isset($images)) {
    $count = 0;
    foreach ($images as $image) {
        if($image['path']){
            $product_image[] = Html::img(
            // Yii::$app->urlManagerFrontEnd->baseUrl . '/uploads/' . $model->id . '/' . $image->id . '.jpg',
                '../../frontend/web/' . $image['path'],
                ['class' => 'file-preview-image']
            );
        }
        $previewConfig[$count] = ['caption' => $image['name'], 'url' => \yii\helpers\Url::to(), 'key' => $count];
        $count++;
    }
}
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo lavrentiev\yii2toastr\Toastr::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'success',
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
            'message' => (!empty($message['message'])) ? $message['message'] : Yii::t('app', 'Message Not Set!'),
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
<div class="form-group no-margin">
    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['product/index'], ['class' => 'btn btn-default btn-circle']) ?>

    <?php if ($model->isNewRecord): ?>
        <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>
    <?php endif; ?>

    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') : '<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

</div>
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
        <!--        <form class="form-horizontal form-row-seperated" action="#">-->
        <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['enctype' => 'multipart/form-data', 'class' => 'horizontal-form']]);
        ?>
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i><?=$this->title; ?>
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
                <!--                <form action="#" class="horizontal-form">-->
                <div class="form-body">
                    <h3 class="form-section"><?= Yii::t('app', 'Product')?></h3>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter product name')]) ?>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <?php
//                            echo $form->field($model, 'barcode', [
//                                'template' => '{label}',
//                                'addon' => [
//                                    'append' => [
//                                        'content' => '<button class="btn btn-default" disabled>' . Yii::$app->params['barcodeCountryCode'] . ' ' . Yii::$app->params['barcodeBusinessCode'] . ' ' . $model->barcode . '</button>',
//                                    ]
//                                ]
//                            ])
//                            ?>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <?= Html::activeLabel($model, 'barcode')?><br>
                                    <button class="btn btn-default" disabled><?php echo Yii::$app->params['barcodeCountryCode'] . ' ' . Yii::$app->params['barcodeBusinessCode'] . ' ' . $model->barcode?></button>
                                    <?= $form->field($model, 'barcode')->hiddenInput()->label(false)?>
                                </div>
                                <div class="col-md-6">
                                    <div id="barcode-image"></div>
                                    <?php
                                    \barcode\barcode\BarcodeGenerator::widget([
                                        'value' => Yii::$app->params['barcodeCountryCode'].Yii::$app->params['barcodeBusinessCode'].$model->barcode,
                                        'elementId' => 'barcode-image',
                                        'type' => 'ean13'
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">

                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'price',
                                ['addon' => [
                                    'append' => [
                                        'content' => '<ins>đ</ins> / '.Html::activeDropDownList($model, 'unit_id', \yii\helpers\ArrayHelper::map(\common\models\Unit::find()->where(['active' => 1])->all(), 'id', 'name')),
                                    ]
                                ]])->textInput(['id'=>'product_price','placeholder' => Yii::t('app', 'Enter product price'), 'onkeyup' => 'javascript:this.value=Comma(this.value);']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'quantity_in_stock',[
                                'addon' => [
                                    'append' => [
                                        'content' => 'kg',
                                    ],
                                ]])->textInput(['id' => 'product_quantity', 'maxlength' => 10, 'placeholder' => Yii::t('app', 'Enter summery of product')]) ?>
                        </div>
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= $form->field($model, 'description')->widget(Zelenin\yii\widgets\Summernote\Summernote::className(), [
                                    'options' => ['row' => 3],
                                ]) ?>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'intro')->widget(Zelenin\yii\widgets\Summernote\Summernote::className(), [
                                'options' => ['row' => 3],
                            ]) ?>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'sold',[
                                'addon' => [
                                    'append' => [
                                        'content' => 'kg',
                                    ]
                                ]
                            ])->textInput(['id' => 'product_sold','maxlength' => 10, 'placeholder' => Yii::t('app', 'Enter sold product')]) ?>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <?= $form->field($model, 'tax',[
                                'addon' => [
                                    'append' => [
                                        'content' => '%',
                                    ]
                                ]
                            ])->textInput(['id' => 'product_tax', 'placeholder' => Yii::t('app', 'Enter tax of product')]) ?>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'productTags')->widget(\kartik\widgets\Select2::className(), [
                                'data' => \yii\helpers\ArrayHelper::map(\common\models\Tag::find()->all(), 'id', 'name'),
                                'options' => [
                                    'placeholder' => Yii::t('app', 'Select tags'),
                                ],
                                'pluginOptions' => [
                                    'tags' => true,
                                    'allowClear' => true,
                                ],

                            ])->label(Yii::t('app','Tags'))?>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <?= $form->field($model, 'productSeasons')->widget(\kartik\widgets\Select2::className(), [
                                'data' => \yii\helpers\ArrayHelper::map(\common\models\Season::find()->all(), 'id', 'name'),
                                'options' => [
                                    'placeholder' => Yii::t('app', 'Select season'),
                                    'multiple' => true,
                                ]
                            ])->label(Yii::t('app','Season'))?>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'category_id')
                                ->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name'),
                                    ['prompt'=>Yii::t('app', 'Select Category')]
                                )->label(Yii::t('app','Categories')) ?>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">

                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <h3 class="form-section"><?=Yii::t('app','Images')?></h3>
                    <div class="row">
                        <div class="col-md-12 ">
                            <?= $form->field($model, 'productImage[]')
                                ->widget(\kartik\widgets\FileInput::className(), [
                                    'options' => [
                                        'accept' => 'image/*',
                                        'multiple' => true,
                                    ],
                                    'pluginOptions' => [
                                        'showCaption' => true,
                                        'allowedFileExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
                                        'showUpload' => false,
                                        'maxFileCount' => 10,
                                        'maxFile' => 10,
                                        'initialPreview' => $product_image,
                                        'overwriteInitial' => true,
                                        'layoutTemplates' => [
                                            'footer' => '<div class="file-thumbnail-footer">'.
                                                '<div class="file-caption-name">{caption}</div>'.
                                                '<div class="file-actions">'.
                                                '<div class="file-footer-buttons">'.
                                                '<button type="button" class="kv-file-remove btn btn-xs btn-default" title="Remove file">'.
                                                '<i class="glyphicon glyphicon-trash text-danger"></i>'.
                                                '</button>'.
                                                '</div>'.
                                                '</div>'.
                                                '</div>'
                                        ]
                                    ]
                                ])->label(Yii::t('app','ProductImages')) ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <div class="row">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <?= $this->blocks['submit']?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END FORM-->
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    /**
     * @return {string}
     */
    function Comma(num) { //function to add commas to textboxes
        num += '';
        num = num.replace(' ', ''); num = num.replace(' ', ''); num = num.replace(' ', '');
        num = num.replace(' ', ''); num = num.replace(' ', ''); num = num.replace(' ', '');
        var x = num.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ' ' + '$2');
        return x1 + x2;
    }

    $("#product_price, #product-quantity_in_stock, #product_tax, #product_sold, #product-barcode #product_quantity").keypress(function (e) {
        return !(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57));
    });
</script>
