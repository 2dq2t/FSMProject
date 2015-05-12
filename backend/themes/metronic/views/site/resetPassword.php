<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ResetPasswordForm */

$this->title = Yii::t('app', 'Reset password');
?>

<!-- BEGIN RESET PASSWORD FORM -->
<?php $form = ActiveForm::begin(['options' => ['class' => 'reset-password-form', 'novalidate' => 'novalidate']]); ?>
<h3><?= Html::encode($this->title) ?></h3>
<p>
    <?= Yii::t('app', 'Please choose your new password:') ?>
</p>
<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9"><?= Yii::t('app', 'New Password') ?></label>
    <?= $form->field($model, 'password')->passwordInput([
        'class' => 'form-control form-control-solid placeholder-no-fix',
        'autocomplete' => 'off',
        'placeholder' => Yii::t('app', 'New Password'),
    ])->label(false) ?>
</div>
<div class="form-actions">
    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success uppercase pull-right']) ?>
</div>
<?php ActiveForm::end(); ?>
<!--</form>-->
<!-- END RESET PASSWORD FORM -->
