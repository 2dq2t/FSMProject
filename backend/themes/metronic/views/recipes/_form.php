<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Recipes */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$path = '';

if($model->image) {
    if(file_exists('../../frontend/web/uploads/recipes/'. $model->id . '/' . $model->image)) {
        $path = Html::img('../../frontend/web/uploads/recipes/' . $model->id . '/' . $model->image, ['class' => 'file-preview-image']);
    }
}
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
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
    ?>
<?php endforeach; ?>


<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">

    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['recipes/index'], ['class' => 'btn btn-default btn-circle']) ?>

    <?php if ($model->isNewRecord): ?>
        <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>
    <?php endif; ?>

    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') : '<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

</div>
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-support"></i><?= $this->title ?>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="form-body">
                    <div class="form-group">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter title')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter alias')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'post_info')->widget(Zelenin\yii\widgets\Summernote\Summernote::className(), [
                            'options' => ['row' => 3],
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'full_post')->widget(Zelenin\yii\widgets\Summernote\Summernote::className(), [
                            'options' => ['row' => 3],
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'image')->widget(\kartik\widgets\FileInput::className(), [
                            'options' => [
                                'accept' => 'image/*',
                                'multiple' => false,
                            ],
                            'pluginOptions' => [
                                'showCaption' => true,
                                'allowedFileExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
                                'showUpload' => false,
                                'maxFileCount' => 10,
                                'maxFile' => 10,
                                'initialPreview' => $path,
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
                        ])->label() ?>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="btn-set pull-right">
                        <?php echo $this->blocks['submit']; ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>