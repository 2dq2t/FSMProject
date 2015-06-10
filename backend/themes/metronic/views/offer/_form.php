<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Offer */
/* @var $form yii\widgets\ActiveForm */
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

    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['offer/index'], ['class' => 'btn btn-default btn-circle']) ?>

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
                    <i class="fa fa-tags"></i><?= $this->title ?>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                    </a>
                    <a href="javascript:;" class="reload">
                    </a>
                    <a href="javascript:;" class="remove">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-body">
                    <div class="form-group">
                        <?= $form->field($model, 'product_id')
                            ->dropDownList(
                                \yii\helpers\ArrayHelper::map(\common\models\Product::find()->where(['active' => 1])->all(), 'id', 'name'),
                                ['prompt'=>Yii::t('app', 'Select Product')]
                            ) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'discount')->textInput(['placeholder' => Yii::t('app', 'Enter offer discount..')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'description')->textarea(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter offer price..')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'start_date')->widget(\kartik\date\DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('app', 'Enter start date ...')],
                            'removeButton' => false,
                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
//                            'language' => 'vi',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'todayHighlight' => true,
//                                'format' => 'yyyy-mm-dd',
                            ]
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'end_date')->widget(\kartik\date\DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('app', 'Enter end date ...')],
                            'removeButton' => false,
                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
//                            'language' => 'vi',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'todayHighlight' => true,
//                                'format' => 'yyyy-mm-dd',
                            ],
                        ]); ?>
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
