<?php

use yii\helpers\Html;
//use mdm\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model yii\web\IdentityInterface */

$this->params['breadcrumbs'][] = $this->title;
$this->title = Yii::t('app', 'Details {modelClass}: ', [
        'modelClass' => Yii::t('app', 'Assignment'),
    ]) . $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Detail');
?>


<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        var_dump($message);
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


<?php $this->beginBlock('submit'); ?>
<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['assignment/index'], ['class' => 'btn btn-default btn-circle']) ?>


<?= Html::submitButton('<i class="fa fa-check"></i> ' . Yii::t('app', 'Save'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'save']) ?>
<?php $this->endBlock('submit'); ?>


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i><?= $this->title?>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $form = \yii\widgets\ActiveForm::begin(); ?>
                <div class="form-body">
                    <div class="form-group">
                        <?= $form->field($model, 'assignments')->widget(\fsm\dualistbox\DualListbox::className(), [
                            'items' => $items,
                            'options' => [
                                'moveOnSelect' => false,
                                'selectorMinimalHeight' => '200'
                            ]
                        ])->label(false)?>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="btn-set pull-right">
                        <?= $this->blocks['submit']?>
                    </div>
                </div>
                <?php \yii\widgets\ActiveForm::end(); ?>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>

