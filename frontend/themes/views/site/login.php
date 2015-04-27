<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

?>
<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'form-login', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>
        <div class="col-md-6">
            <section class="section register inner-right-xs">
                <h2 class="bordered">Đăng Nhập</h2>
                <p>Xin chào, Chào mừng bạn đến với hệ thống FSM</p>
                <div class="field-row">
                    <label>Tên Đăng Nhập</label>
                    <?= $form->field($model, 'username', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 45,
                        ],
                    ])->label(false); ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Mật Khẩu</label>
                    <?= $form->field($model, 'password', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 100,
                        ],
                    ])->passwordInput()->label(false); ?>
                </div><!-- /.field-row -->

                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>
                <div class="buttons-holder">
                    <button type="submit" class="le-button huge" name="submit">Đăng nhập</button>
                </div><!-- /.buttons-holder -->
            </section>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
