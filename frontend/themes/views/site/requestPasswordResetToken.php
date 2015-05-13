<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

?>
<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'form-request-reset', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>
        <div class="col-md-6">
            <section class="section register inner-right-xs">
                <p>Bạn hãy điền địa chỉ Email, chúng tôi sẽ gửi thông tin thay đổi đến đó.</p>
                <div class="field-row">
                    <label>Email</label>
                    <?= $form->field($model, 'email', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div><!-- /.field-row -->
                <div class="buttons-holder">
                    <button type="submit" class="le-button huge" name="submit">Gửi Email</button>
                </div><!-- /.buttons-holder -->
            </section>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<footer id="footer" class="color-bg">