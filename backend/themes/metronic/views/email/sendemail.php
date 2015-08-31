<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\SlideShow */

$this->title = Yii::t('app', 'Send Email');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Email'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
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
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">

    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['email/index'], ['class' => 'btn btn-default btn-circle']) ?>

    <?= Html::submitButton('<i class="fa fa-check"></i> ' . Yii::t('app', 'Send'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

</div>
<?php $this->endBlock('submit'); ?>



<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL])?>
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-send"></i><?= $this->title ?>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-to"><?= Yii::t('app', 'Send To')?></label>
                            <div class="col-sm-10">
                                <select name="to" id="input-to" class="form-control">
                                    <option value="customer_all"><?= Yii::t('app', 'All Customers')?></option>
                                    <option value="customer"><?= Yii::t('app', 'Send by Customers')?></option>
                                    <option value="product"><?= Yii::t('app', 'Send by Products')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group to" id="to-customer">
                            <div class="col-sm-2">
                                <?= Html::activeLabel($model, 'customer')?>
                            </div>
                            <div class="col-sm-10">
                                <?php
                                echo $form->field($model, 'customer')->widget(\kartik\widgets\Select2::className(), [
                                    'data' => \yii\helpers\ArrayHelper::map(\common\models\Guest::find()->all(), 'email', 'email'),
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Select customers to sending email'),
                                        'multiple' => true
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="form-group to" id="to-product">
                            <div class="col-sm-2">
                                <?= Html::activeLabel($model, 'product')?>
                            </div>
                            <div class="col-sm-10">
                                <?php
                                echo $form->field($model, 'product')->widget(\kartik\widgets\Select2::className(), [
                                    'data' => \yii\helpers\ArrayHelper::map(\common\models\Product::find()->where(['active' => \common\models\Product::STATUS_ACTIVE])->all(), 'id', 'name'),
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Select product to sending email'),
                                        'multiple' => true
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2">
                                <?= Html::activeLabel($model, 'subject')?>
                            </div>
                            <div class="col-md-10">
                                <?= $form->field($model, 'subject')->textInput(['placeholder' => Yii::t('app', 'Enter subject new email')])->label(false)?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2">
                                <?= Html::activeLabel($model, 'message')?>
                            </div>
                            <div class="col-md-10">
                                <?= $form->field($model, 'message')->widget(\Zelenin\yii\widgets\Summernote\Summernote::className())->label(false)?>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="col-md-offset-7 col-md-6">
                            <?php echo $this->blocks['submit']; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end()?>
    </div>
</div>

<?php
$js = <<<SCRIPT
$('select[name=\'to\']').on('change', function() {
	$('.to').hide();

	$('#to-' + this.value.replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
SCRIPT;
$this->registerJs($js);
?>