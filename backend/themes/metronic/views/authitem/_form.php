<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo \kartik\alert\Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : \kartik\alert\Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
            'delay' => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
            'showSeparator' => true,
            'options' => ['format' => 'raw']
        ]);
    }
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<!--<div class="form-group no-margin">-->

<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['authitem/index'], ['class' => 'btn btn-default btn-circle']) ?>

<?php if ($model->isNewRecord): ?>
    <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>
<?php endif; ?>

<?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') : '<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

<!--</div>-->
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
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
                <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
                    <div class="form-body">
                        <?= $form->field($model, 'oldname', ['template' => '{input}'])->input('hidden'); ?>
                        <?= $form->field($model, 'type', ['template' => '{input}'])->input('hidden'); ?>
                        <?= $form->field($model, 'name', [
                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                        ])->textInput(['maxlength' => 64, 'placeholder' => Yii::t('app', 'Enter permission name')]) ?>

                        <?= $form->field($model, 'description', [
                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                        ])->textarea(['rows' => 3, 'placeholder' => Yii::t('app', 'Enter permission description')]) ?>

                        <?= (!empty($rules)) ? $form->field($model, 'rule_name', [
                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                        ])->dropDownList($rules, ['prompt' => 'Choose rule']) : '' ?>

                        <?= $form->field($model, 'children', [
                            'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                        ])->multiselect($items, [
                                'height' => '100%',
                                'container' => ['class' => 'bg-white']
                            ])?>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <?= $this->blocks['submit']?>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
