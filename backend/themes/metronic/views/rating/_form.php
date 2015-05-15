<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Rating */
/* @var $form yii\widgets\ActiveForm */
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo Alert::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : Yii::t('app', 'Message Not Set!'),
        'showSeparator' => true,
        'delay' => 3000,
    ]);
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">

    <?= Html::a(Yii::t('app', 'Back'), ['rating/index'], ['class' => 'btn btn-danger']) ?>

    <?php if ($model->isNewRecord): ?>
        <?= Html::submitButton(Yii::t('app', 'Save & Go next'), ['class' => 'btn btn-success', 'name' => 'action', 'value' => 'next']) ?>
    <?php endif; ?>

    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'action' , 'value' => 'save']) ?>

</div>
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-star"></i><?= $this->title ?>
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
                        <?= $form->field($model, 'rating')->widget(\kartik\widgets\StarRating::classname(), [
                            'name' => 'rating_21',
                            'pluginOptions' => [
                                'min' => 0,
                                'max' => 5,
                                'step' => 0.5,
                                'size' => 'lg',
                                'starCaptions' => [
                                    '0.5' => 'Extremely Poor',
                                    1 => 'Very Poor',
                                    '1.5' => 'Poor',
                                    2 => 'Ok',
                                    '2.5' => 'Good',
                                    3 => 'Very Good',
                                    '3.5' => 'Extremely Good',
                                ],
                                'starCaptionClasses' => [
                                    '0.5' => 'text-danger',
                                    1 => 'text-danger',
                                    '1.5' => 'text-warning',
                                    2 => 'text-info',
                                    '2.5' => 'text-primary',
                                    3 => 'text-success',
                                    '3.5' => 'text-success'
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-group">
                        <?= $form->field($model, 'description')->textInput(['maxlength' => 100]) ?>
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