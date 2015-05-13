<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

?>

<div class="row">
    <?php $form = ActiveForm::begin(['id' => 'form-login', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>
    <div class="col-md-6 col-md-offset-3">
        <section class="section register inner-right-xs">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">ĐĂNG NHẬP</legend
                <p>Xin chào, Chào mừng bạn đến với Fresh Garden</p>

                <div class="field-row">
                    <label>Tên Đăng Nhập</label>
                    <?= $form->field($model, 'username', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div>
                <!-- /.field-row -->

                <div class="field-row">
                    <label>Mật Khẩu</label>
                    <?= $form->field($model, 'password', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->passwordInput()->label(false); ?>
                </div>
                <!-- /.field-row -->

                <?= $form->field($model, 'rememberMe')->checkbox()->label(Html::tag('span','Remember Me',['class' => 'content-color bold'])) ?>
                <div class="form-group">
                    <div style="color:#999;margin:1em 0">
                        <span class="content-color bold">Quên mật khẩu?</span> <?= Html::a('click để lấy lại', ['site/request-password-reset']) ?>.
                    </div>
                    <div class="buttons-holder text-center">
                        <button type="submit" class="le-button huge" name="submit">Đăng nhập</button>
                    </div>
                    <!-- /.buttons-holder -->
                </div>
            </fieldset>
        </section>
    </div>
    <?php ActiveForm::end(); ?>
</div>

