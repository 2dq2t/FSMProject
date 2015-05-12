<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\PasswordResetRequestForm */

$this->title = Yii::t('app', 'Request password reset');
//$this->params['breadcrumbs'][] = $this->title;
?>
<!-- BEGIN FORGOT PASSWORD FORM -->
<?php $form = ActiveForm::begin(['options' => ['class' => 'forget-f', 'novalidate' => 'novalidate']]); ?>
<h3><?= Html::encode($this->title) ?></h3>
<p>
    <?= Yii::t('app', 'Enter your e-mail address below to reset your password.') ?>
</p>
<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9"><?= Yii::t('app', 'Email') ?></label>
    <?= $form->field($model, 'email')->textInput([
        'class' => 'form-control form-control-solid placeholder-no-fix',
        'autocomplete' => 'off',
        'placeholder' => Yii::t('app', 'Email'),
    ])->label(false) ?>
</div>
<div class="form-actions">
<!--    <button type="button" id="back-btn" class="btn btn-default">Back</button>-->
    <?= Html::a(Yii::t('app', 'Back'), ['/site/login'], ['class'=>'btn btn-default']) ?>
    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success uppercase pull-right']) ?>
</div>
<?php ActiveForm::end(); ?>
<!--</form>-->
<!-- END FORGOT PASSWORD FORM -->
