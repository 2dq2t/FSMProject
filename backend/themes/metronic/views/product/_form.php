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
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-shopping-cart"></i>Product
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="reload" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
<!--                <form action="#">-->
                    <div class="form-body">
                        <div class="form-group">
                            <?= $form->field($model, 'active')
                                ->widget(\kartik\switchinput\SwitchInput::className(), [
                                    'pluginOptions' => [
                                        'onColor' => 'success',
                                        'offColor' => 'default',
                                        'onText'=> 'Active',
                                        'offText'=> 'Inactive'
                                    ]
                                ]) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'barcode')->textInput(['maxlength' => 20, 'placeholder' => 'Nhập barcode..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'name',[
                                'addon' => [
                                    'append' => [
                                        'content' => Html::activeDropDownList($model, 'unit_id', \yii\helpers\ArrayHelper::map(\common\models\Unit::find()->where(['active' => 1])->all(), 'id', 'name')),
                                    ],
                                ]])->textInput(['maxlength' => 255, 'placeholder' => 'Nhập tên sản phẩm..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'price',
                                ['addon' => [
                                    'append' => [
                                        'content' => '<ins>đ</ins>'
                                    ]
                                ]])->textInput(['placeholder' => 'Nhập giá sản phẩm..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'description')->widget(Zelenin\yii\widgets\Summernote\Summernote::className(), [
                                'options' => ['row' => 3],
                            ]) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'quantity')->textInput(['maxlength' => 10, 'placeholder' => 'Nhập số lượng sản phẩm..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'sold')->textInput(['maxlength' => 10, 'placeholder' => 'Nhập số lượng đã bán..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'tax')->textInput(['placeholder' => 'Nhập thuế sản phẩm..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'fee')->textInput(['placeholder' => 'Nhập phí sản phẩm..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'tag')->textInput(['maxlength' => 100, 'placeholder' => 'Nhập tag sản phẩm..']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'session_id')
                                ->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\Session::find()->where(['active' => 1])->all(), 'id', 'name'),
                                    ['prompt'=>'Chọn mùa của sản phẩm']
                                ) ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="btn-set pull-right">
                            <?php echo $this->blocks['submit']; ?>
                        </div>
                    </div>
<!--                </form>-->
                <!-- END FORM-->
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box green">
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
                        <?= $form->field($model, 'category_id')
                            ->dropDownList(
                                \yii\helpers\ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name'),
                                ['prompt'=>'Select Category']
                            ) ?>
                    </div>
                </div>
                <div class="form-actions right">
                    <?php echo $this->blocks['submit']; ?>
                </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
    <div class="col-md-6">

        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box green">
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
                        <?= $form->field($productImages, 'product_image[]')
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
                            ]) ?>
                    </div>
                </div>
                <div class="form-actions right">
                    <div class="row">
                        <?php echo $this->blocks['submit']; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
    <?php ActiveForm::end(); ?>
</div>
