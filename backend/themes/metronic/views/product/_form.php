<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
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
                '../../' . $image['path'],
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
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay' => 3000, //This delay is how long before the message shows
//            'pluginOptions' => [
//                'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
//                // 'placement' => [
//                //     'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
//                //     'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
//                // ]
//            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-action">
    <div class="row">
        <div class="col-md-12">
            <?php if (!$model->isNewRecord): ?>
                <?= Html::a('Preview', ['product/index'], ['class' => 'btn btn-info', 'target' => '_blank']) ?>
            <?php endif; ?>
            <?= Html::a('Back', ['product/index'], ['class' => 'btn default']) ?>

            <?php if ($model->isNewRecord): ?>
                <?= Html::submitButton('Save & Go next', ['class' => 'btn btn-success', 'name' => 'action', 'value' => 'next']) ?>
            <?php endif; ?>

            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'name' => 'action' , 'value' => 'save']) ?>
        </div>
    </div>
</div>
<?php $this->endBlock('submit'); ?>

<div class="row">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data']]); ?>
    <div class="col-md-6 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-shopping-cart"></i>Product
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                    <a href="" class="reload">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'active', ['label'=>'Active', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'active',
                                    ['template' => '<div class="col-md-12" style="margin-left: 13px;">{input}</div><div class="col-md-12">{error}</div>'])
                                    ->widget(\kartik\switchinput\SwitchInput::className(), [
                                        'pluginOptions' => [
                                            'onColor' => 'success',
                                            'offColor' => 'default',
                                            'onText'=> 'Active',
                                            'offText'=> 'Inactive'
                                        ]
                                    ])
                                    ->label(false); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'barcode', ['label'=>'Barcode', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'barcode',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                    ->label(false)->textInput(['maxlength' => 20, 'placeholder'=>'Enter barcode...']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'name', ['label'=>'Product Name', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'name',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                    ->label(false)->textInput(['maxlength' => 100, 'placeholder'=>'Enter product name...']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'price', ['label'=>'Price', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <?= $form->field($model, 'price',
                                        ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>',
                                            'addon' => [
                                                'append' => [
                                                    'content' => '<ins>đ</ins>'
                                                ]
                                            ]])->label(false)->textInput(['placeholder'=>'Enter product price...']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'old_price', ['label'=>'Old Price', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'old_price',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>',
                                        'addon' => [
                                            'append' => [
                                                'content' => '<ins>đ</ins>'
                                            ]
                                        ]])->label(false)->textInput(['placeholder'=>'Enter product old price...']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'description', ['label'=>'Description', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'description',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                    ->label(false)->widget(Zelenin\yii\widgets\Summernote\Summernote::className(), [
                                        'clientOptions' => [
                                            'height' => 180,
                                            'focus' => false,
                                            'codemirror' => [
                                                'themes' => 'paper',
                                            ]
                                        ]
                                    ]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'total', ['label'=>'Total', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'total',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                    ->label(false)->textInput(['placeholder'=>'Enter total product...']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'sold', ['label'=>'Sold', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'sold',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                    ->label(false)->textInput(['placeholder'=>'Enter sold product...']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'tax', ['label'=>'Tax', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'tax',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>',
                                        'addon' => [
                                            'append' => [
                                                'content' => '<ins>đ</ins>'
                                            ]
                                        ]])->label(false)->textInput(['placeholder'=>'Enter product tax...']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'fee', ['label'=>'Fee', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'fee',
                                    ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>',
                                        'addon' => [
                                            'append' => [
                                                'content' => '<ins>đ</ins>'
                                            ]
                                        ]])->label(false)->textInput(['placeholder'=>'Enter product fee...']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions right">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <?php echo $this->blocks['submit']; ?>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
    <div class="col-md-6">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-tags"></i>Categories
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                    <a href="" class="reload">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'category_id', ['label'=>'Category', 'class'=>'col-md-3 control-label']); ?>
                            <div class="col-md-9">
                                <?= $form->field($model, 'category_id', ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                    ->dropDownList(
                                        \yii\helpers\ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name'),
                                        ['prompt'=>'Select Category']
                                    )->label(false); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions right">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <?php echo $this->blocks['submit']; ?>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-image"></i>Images
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                    <a href="" class="reload">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <?= Html::activeLabel($productImages, 'product_image[]', ['label'=>'Images', 'class'=>'col-md-3 control-label']); ?>
                        <div class="col-md-9">
                            <?= $form->field($productImages, 'product_image[]', ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                ->widget(\kartik\widgets\FileInput::className(), [
                                    'options' => [
                                        'accept' => 'image/*',
                                        'multiple' => true,
                                    ],
                                    'pluginOptions' => [
                                        'showCaption' => true,
//                                                'uploadUrl' => \yii\helpers\Url::to(),
//                                                'uploadAsync' => true,
                                        'allowedFileExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
                                        'showUpload' => false,
                                        'maxFileCount' => 10,
                                        'maxFile' => 10,
                                        'initialPreview' => $product_image,
                                        'overwriteInitial' => true,
//                                                'initialPreviewConfig' => $previewConfig,
//                                                'initialPreviewShowDelete' => true,
//                                                'append' => true,
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
                                ])->label(false); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <?php echo $this->blocks['submit']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
    <?php ActiveForm::end(); ?>
</div>
