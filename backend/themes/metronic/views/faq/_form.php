<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */
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

    <?= Html::a(Yii::t('app', 'Back'), ['faq/index'], ['class' => 'btn default']) ?>

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
                    <i class="fa fa-support"></i><?= $this->title ?>
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
                        <?= $form->field($model, 'question')->textarea(['rows' => 3]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'answer')->widget(Zelenin\yii\widgets\Summernote\Summernote::className(), [
                            'options' => ['row' => 3],
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'active')->checkbox() ?>
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