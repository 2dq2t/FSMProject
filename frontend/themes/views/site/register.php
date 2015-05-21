<?php

use yii\widgets\ActiveForm;
use common\models\City;
use kartik\widgets\DepDrop;

?>
<div class="row">
    <?php $form = ActiveForm::begin(['id' => 'form-register', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>
    <div class="col-md-6 col-md-offset-3">
        <section class="section register inner-right-xs">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">TẠO TÀI KHOẢN</legend
                <p>Hãy đăng kí để trở thành khách hàng của Fresh Garden</p>

                <div class="field-row">
                    <label>Họ và tên<span class="required"> *</span></label>
                    <?= $form->field($modelGuest, 'full_name', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div>
                <!-- /.field-row -->

                <div class="field-row">
                    <label>Tên đăng nhập<span class="required"> *</span></label>
                    <?= $form->field($modelCustomer, 'username', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div>
                <!-- /.field-row -->

                <div class="field-row">
                    <label>Mật khẩu<span class="required"> *</span></label>
                    <?= $form->field($modelCustomer, 'password', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->passwordInput()->label(false); ?>
                </div>
                <!-- /.field-row -->

                <div class="field-row">
                    <label>Xác Nhận Mật khẩu<span class="required"> *</span></label>
                    <?= $form->field($modelCustomer, 're_password', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->passwordInput()->label(false); ?>
                </div>
                <!-- /.field-row -->

                <div class="field-row">
                    <label>Email<span class="required"> *</span></label>
                    <?= $form->field($modelGuest, 'email', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div>
                <!-- /.field-row -->

                <div class="field-row">
                    <label>Số điện thoại<span class="required"> *</span></label>
                    <?= $form->field($modelGuest, 'phone_number', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 15,
                        ],
                    ])->label(false); ?>
                </div>
                <!-- /.field-row -->

                <div class="form-group">
                    <div class="buttons-holder text-right" style="margin: 1em 0">
                        <span class="required">* </span>Thông tin bắt buộc
                    </div>
                    <div class="buttons-holder text-center">
                        <button type="submit" class="le-button huge" name="submit">Đăng ký</button>
                    </div>
                    <!-- /.buttons-holder -->
                </div>
            </fieldset>
        </section>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<footer id="footer" class="color-bg">