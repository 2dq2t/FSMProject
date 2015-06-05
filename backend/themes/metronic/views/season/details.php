<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Season */

$this->title = Yii::t('app', 'Details {modelClass}: ', [
        'modelClass' => Yii::t('app', 'Season'),
    ]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Seasons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
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

<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['order/index'], ['class' => 'btn btn-default btn-circle']) ?>

<?= Html::submitButton('<i class="fa fa-check"></i> ' . Yii::t('app', 'Submit'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

<!--</div>-->
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
                <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <?= $form->field($model, 'products_list', [
                                    'template' => "<div class='col-md-12'>{input}{error}</div>"
                                ])->widget(duallistbox\duallistbox\Widget::className(), [
                                    'data' => \common\models\Product::find(),
                                    'data_id'=> 'id',
                                    'data_value'=> 'name',
                                    'options' => [
                                        'nonSelectedListLabel' => Yii::t('app', 'Product'),
                                        'selectedListLabel' => Yii::t('app', 'Product in {season}', ['season' => $model->name]),
//                                        'moveOnSelect' => false,
                                    ]
                                ])->label(false)?>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-6 col-md-6">
                                <?= $this->blocks['submit']?>
                            </div>
                        </div>
                    </div>
                <?php \yii\widgets\ActiveForm::end(); ?>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>