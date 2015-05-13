<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */
?>
<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'form-request-reset', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>
            <div class="col-md-6">
                <section class="section register inner-right-xs">
                    <p>Bạn hãy điền mật khẩu mới</p>
                    <div class="field-row">
                        <label>Mật khẩu</label>
                        <?= $form->field($model, 'password', [
                            'inputOptions' => [
                                'class' => 'le-input',
                                'maxlength' => 255,
                            ],
                        ])->passwordInput()->label(false); ?>
                    </div><!-- /.field-row -->
                    <div class="buttons-holder">
                        <button type="submit" class="le-button huge" name="submit">Lưu thay đổi</button>
                    </div><!-- /.buttons-holder -->
                 </section>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<footer id="footer" class="color-bg">
