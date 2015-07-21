<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Sign in');
//$this->params['breadcrumbs'][] = $this->title;

if(Yii::$app->session->getFlash('success')) {
    echo '<div class="alert alert-success">' . Yii::$app->session->getFlash('success') . "</div>\n";
}
if(Yii::$app->session->getFlash('error')) {
    echo '<div class="alert alert-error">' . Yii::$app->session->getFlash('error') . "</div>\n";
}
?>


<!-- BEGIN LOGIN FORM -->
<!--<form class="login-form" method="post">-->
<?php $form = ActiveForm::begin(['options' => ['class' => 'login-form', 'novalidate' => 'novalidate']]); ?>
<h3 class="form-title"><?= Html::encode($this->title) ?></h3>
<div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
        <span><?= Yii::t('app', 'Enter any email and password.') ?> </span>
</div>
<div class="form-group">
    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
    <label class="control-label visible-ie8 visible-ie9"><?= Yii::t('app', 'Username') ?></label>
    <?= $form->field($model, 'email')->textInput([
        'class' => 'form-control form-control-solid placeholder-no-fix',
        'autocomplete' => 'off',
        'placeholder' => Yii::t('app', 'BLoginEmail'),
    ])->label(false) ?>
</div>
<div class="form-group">
    <label class="control-label visible-ie8 visible-ie9"><?= Yii::t('app', 'Password') ?></label>
    <?= $form->field($model, 'password')->passwordInput([
        'class' => 'form-control form-control-solid placeholder-no-fix',
        'autocomplete' => 'off',
        'placeholder' => Yii::t('app', 'BLoginPassword')
    ])->label(false) ?>
</div>
<div class="form-actions">
    <?= Html::submitButton(Yii::t('app','LoginLabel'), ['class' => 'btn btn-success uppercase', 'name' => 'login-button']) ?>
    <?= $form->field($model, 'rememberMe')->checkbox() ?>
    <?= Html::a(Yii::t('app', 'ForgottenPasswordLabel'), ['/site/request-password-reset'], ['class' => 'forget-password'])?>
</div>
<!--</form>-->
<?php ActiveForm::end(); ?>
<!-- END LOGIN FORM -->

