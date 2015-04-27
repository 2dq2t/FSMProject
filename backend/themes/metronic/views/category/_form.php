<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo Alert::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
        'showSeparator' => true,
        'delay' => 300000, //This delay is how long before the message shows
//            'pluginOptions' => [
//                'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
//                'placement' => [
//                    'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
//                    'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
//                ]
//            ]
    ]);
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">
    <?php if (!$model->isNewRecord): ?>
        <?= Html::a('Preview', ['category/index'], ['class' => 'btn btn-info', 'target' => '_blank']) ?>
    <?php endif; ?>
    <?= Html::a('Back', ['category/index'], ['class' => 'btn default']) ?>

    <?php if ($model->isNewRecord): ?>
        <?= Html::submitButton('Save & Go next', ['class' => 'btn btn-success', 'name' => 'action', 'value' => 'next']) ?>
    <?php endif; ?>

    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'name' => 'action' , 'value' => 'save']) ?>

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
<!--                            <label class="control-label">Text</label>-->
<!--                            <input type="text" class="form-control" placeholder="Enter text">-->
                            <?= $form->field($model, 'name')->textInput(['maxlength' => 45, 'placeholder' => 'Enter category name']) ?>
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
