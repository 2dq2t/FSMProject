<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $item \yii\rbac\Item */
?>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo lavrentiev\yii2toastr\Toastr::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'success',
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'message' => (!empty($message['message'])) ? trim(preg_replace('/\s+/', ' ', $message['message'])) : 'Message Not Set!',
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

<?php $this->beginBlock('submit');
?>
    <!--<div class="form-group no-margin">-->

<?= Html::a('<i class="fa fa-angle-left"></i> ' . Yii::t('app', 'Back'), ['role/index'], ['class' => 'btn btn-default btn-circle']) ?>

<?php if ($model->isNewRecord): ?>
    <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>
<?php endif; ?>

<?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') : '<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'save']) ?>

    <!--</div>-->
<?php $this->endBlock('submit'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i><?= $this->title ?>
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
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => 64, 'placeholder' => Yii::t('app', 'Enter role name')]) ?>
                            <?= $form->field($model, 'oldName', ['template' => '{input}'])->input('hidden'); ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'description')->textarea(['rows' => 3, 'placeholder' => Yii::t('app', 'Enter role description')]) ?>
                        </div>

                        <div class="form-group">
                            <?= $form->field($model, 'rule_name')->dropDownList(\yii\helpers\ArrayHelper::map(\Yii::$app->getAuthManager()->getRules(), 'name', 'name'), ['prompt' => 'Choose rule']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'data')->textarea(['rows' => 6, 'placeholder' => Yii::t('app', 'Enter role data')])?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($model, 'items')->widget(\fsm\dualistbox\DualListbox::className(), [
                                'items' => $item,
                                'options' => [
                                    'moveOnSelect' => false,
                                    'selectorMinimalHeight' => '400'
                                ]
                            ])->label(false)?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="btn-set pull-right">
                            <?= $this->blocks['submit'] ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <!-- END FORM-->
                </div>
            </div>
        </div>
    </div>