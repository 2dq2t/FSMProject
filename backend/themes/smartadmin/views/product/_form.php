<?php

use yii\helpers\Html;
use yii\widgets\BackendWidget;
use kartik\form\ActiveForm;
use Zelenin\yii\widgets\Summernote;
use yii\helpers\ArrayHelper;
use backend\models\Category;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$productImage = [];
$previewConfig = [];

if(isset($images)) {
    $count = 0;
    foreach ($images as $image) {
        if($image['Path']){
            $productImage[] = Html::img(
            // Yii::$app->urlManagerFrontEnd->baseUrl . '/uploads/' . $model->id . '/' . $image->id . '.jpg',
                '../../' . $image['Path'],
                ['class' => 'file-preview-image']
            );
        }
        $previewConfig[$count] = ['caption' => $image['Name'], 'url' => \yii\helpers\Url::to(), 'key' => $count];
        $count++;
    }
}
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo \kartik\widgets\Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
        'showSeparator' => true,
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]);
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
    <div class="form-action">
        <div class="row">
            <div class="col-md-12">
                <?php if (!$model->isNewRecord): ?>
                    <?= Html::a('Preview', ['product/index'], ['class' => 'btn btn-info', 'target' => '_blank']) ?>
                <?php endif; ?>
                <?= Html::a('Back', ['product/index'], ['class' => 'btn btn-danger']) ?>

                <?php if ($model->isNewRecord): ?>
                    <?= Html::submitButton('Save & Go next', ['class' => 'btn btn-success', 'name' => 'action', 'value' => 'next']) ?>
                <?php endif; ?>

                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'name' => 'action' , 'value' => 'save']) ?>
            </div>
        </div>
    </div>
<?php $this->endBlock('submit'); ?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']]); ?>

    <!-- <form action="" class="smart-form">
    </form> -->

    <section id="widget-grid" class="">

        <!-- row -->
        <div class="row">

            <!-- NEW WIDGET START -->
            <article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">

                    <header role="heading">
                        <span class="widget-icon"> <i class="fa fa-lg fa-fw fa-shopping-cart"></i> </span>
                        <h2>Product</h2>

                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

                    <!-- widget div-->
                    <div role="content">

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->

                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                            <fieldset>
                                <legend><?php echo $this->title; ?></legend>
                                <div class="form-group">
                                    <?= Html::activeLabel($model, 'Active', ['label'=>'Active', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'Active',
                                            ['template' => '<div class="col-md-12" style="margin-left: 13px">{input}</div><div class="col-md-12">{error}</div>'])
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
                                    <?= Html::activeLabel($model, 'Barcode', ['label'=>'Barcode', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'Barcode',
                                            ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                            ->label(false)->textInput(['placeholder'=>'Enter barcode...']); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= Html::activeLabel($model, 'Name', ['label'=>'Product Name', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'Name',
                                            ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                            ->label(false)->textInput(['maxlength' => 100, 'placeholder'=>'Enter product name...']); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= Html::activeLabel($model, 'SellPrice', ['label'=>'Price', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'SellPrice',
                                            ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>',
                                                'addon' => [
                                                    'append' => [
                                                        'content' => '<ins>đ</ins>'
                                                    ]
                                                ]])->label(false)->textInput(['placeholder'=>'Enter product price...']); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= Html::activeLabel($model, 'Description', ['label'=>'Description', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'Description',
                                            ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                            ->label(false)->widget(Summernote\Summernote::className(), [
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
                                    <?= Html::activeLabel($model, 'Total', ['label'=>'Total', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'Total',
                                            ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                            ->label(false)->textInput(['placeholder'=>'Enter total product...']); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= Html::activeLabel($model, 'Sold', ['label'=>'Sold', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'Sold',
                                            ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                            ->label(false)->textInput(['placeholder'=>'Enter sold product...']); ?>
                                    </div>
                                </div>

                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo $this->blocks['submit']; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- </form> -->

                        </div>
                        <!-- end widget content -->

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end widget -->

            </article>
            <!-- WIDGET END -->

            <!-- NEW WIDGET START -->
            <article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-sortable" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget" style>


                    <header role="heading">
                        <span class="widget-icon"> <i class="fa fa-lg fa-fw fa-tags"></i> </span>
                        <h2>Categories</h2>

                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

                    <!-- widget div-->
                    <div role="content">

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->

                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                            <!--                        <form class="form-horizontal">-->

                            <fieldset>
                                <legend>Select Category</legend>
                                <div class="form-group">
                                    <?= Html::activeLabel($model, 'CategoryId', ['label'=>'Category', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($model, 'CategoryId', ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
                                            ->dropDownList(
                                                ArrayHelper::map(Category::find()->all(), 'Id', 'Name'),
                                                ['prompt'=>'Select Category']
                                            )->label(false); ?>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo $this->blocks['submit']; ?>
                                    </div>
                                </div>
                            </div>

                            <!--                        </form>-->

                        </div>
                        <!-- end widget content -->

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end widget -->

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-sortable" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget" style>


                    <header role="heading">
                        <span class="widget-icon"> <i class="fa fa-lg fa-fw fa-photo"></i> </span>
                        <h2>Images</h2>

                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

                    <!-- widget div-->
                    <div role="content">

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->

                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                            <!--                        <form class="form-horizontal">-->

                            <fieldset>
                                <legend>Select Images</legend>
                                <div class="form-group">
                                    <?= Html::activeLabel($productImages, 'productImage[]', ['label'=>'Images', 'class'=>'col-md-2 control-label']); ?>
                                    <div class="col-md-10">
                                        <?= $form->field($productImages, 'productImage[]', ['template' => '<div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'])
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
                                                    'initialPreview' => $productImage,
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
                            </fieldset>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo $this->blocks['submit']; ?>
                                    </div>
                                </div>
                            </div>

                            <!--                        </form>-->

                        </div>
                        <!-- end widget content -->

                    </div>
                    <!-- end widget div -->

                </div>
                <!-- end widget -->

            </article>
            <!-- WIDGET END -->

        </div>

        <!-- end row -->

    </section>

<?php ActiveForm::end(); ?>