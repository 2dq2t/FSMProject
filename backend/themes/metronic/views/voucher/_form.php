<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */
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
        'delay' => 3000
    ]);
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">

    <?= Html::a(Yii::t('app', 'Back'), ['voucher/index'], ['class' => 'btn default']) ?>

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
                        <?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter name of voucher')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'code')->textInput(['maxlength' => 32, 'placeholder' => Yii::t('app', 'Enter code of voucher')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'discount',[
                            'addon' => [
                                'append' => [
                                    'content' => '%',
                                ],
                            ]])->textInput(['placeholder' => Yii::t('app', 'Enter voucher discount')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'start_date')->widget(\kartik\date\DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('app', 'Enter start date ...')],
                            'removeButton' => false,
                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                            'language' => 'vi',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'todayHighlight' => true,
                                'format' => 'yyyy-mm-dd',
                            ]
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'end_date')->widget(\kartik\date\DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('app', 'Enter end date ...')],
                            'removeButton' => false,
                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                            'language' => 'vi',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'todayHighlight' => true,
                                'format' => 'yyyy-mm-dd',
                            ],
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'active')
                            ->checkbox() ?>
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
