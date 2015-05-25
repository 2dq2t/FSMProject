<?php

use yii\widgets\ActiveForm;
$this->title = 'Đăng kí';

?>
<div class="container content-inner">

    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <div class="box">
                <div class="box-heading">Account</div>
                <div class="list-group">
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/login"
                       class="list-group-item">Login</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/register"
                        class="list-group-item">Register</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/forgotten"
                        class="list-group-item">Forgotten Password</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account"
                       class="list-group-item">My Account</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/address"
                       class="list-group-item">Address Books</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/wishlist"
                        class="list-group-item">Wish List</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/order"
                        class="list-group-item">Order History</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/download"
                        class="list-group-item">Downloads</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/reward"
                        class="list-group-item">Reward Points</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/return"
                        class="list-group-item">Returns</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/transaction"
                        class="list-group-item">Transactions</a> <a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/newsletter"
                        class="list-group-item">Newsletter</a><a
                        href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/recurring"
                        class="list-group-item">Recurring payments</a>
                </div>
            </div>
        </column>
        <div id="content" class="col-sm-9">
            <ul class="breadcrumb">
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i
                            class="fa fa-home"></i></a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account">Account</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/register">Register</a>
                </li>
            </ul>
            <h2>Register Account</h2>

            <p>If you already have an account with us, please login at the <a
                    href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/login">login page</a>.</p>
            <?php $form = ActiveForm::begin(['id' => 'form-register', 'method' => 'post', 'options' => ['class' => 'form-horizontal']]); ?>

            <fieldset>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="full_name">Họ và tên</label>

                    <div class="col-sm-10">
                        <?= $form->field($modelGuest, 'full_name', [
                            'inputOptions' => [
                                'class' => 'form-control',
                                'maxlength' => 255,
                                'id' => 'full_name',
                                'placeholder' => 'Họ và tên',
                            ],
                        ])->label(false); ?>
                    </div>
                </div>
                <!-- /.field-row -->

                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="username">Tên đăng nhập</label>
                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'username', [
                            'inputOptions' => [
                                'class' => 'form-control',
                                'maxlength' => 255,
                                'id'=>'username',
                                'placeholder' => 'Tên đăng nhập',
                            ],
                        ])->label(false); ?>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="password">Mật khẩu</label>
                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'password', [
                            'inputOptions' => [
                                'class' => 'form-control',
                                'maxlength' => 255,
                                'id'=>'password',
                                'placeholder' => 'Mật khẩu',
                            ],
                        ])->passwordInput()->label(false); ?>
                    </div>
                </div>
                <!-- /.field-row -->

                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="re_password">Xác Nhận Mật khẩu</label>
                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 're_password', [
                            'inputOptions' => [
                                'class' => 'form-control',
                                'maxlength' => 255,
                                'id'=>'re_password',
                                'placeholder' => 'Nhập lại mật khẩu',
                            ],
                        ])->passwordInput()->label(false); ?>
                    </div>
                </div>
                <!-- /.field-row -->

                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="email">Email</label>
                    <div class="col-sm-10">
                        <?= $form->field($modelGuest, 'email', [
                            'inputOptions' => [
                                'class' => 'form-control',
                                'maxlength' => 255,
                                'id'=>'email',
                                'placeholder' => 'Email',
                            ],
                        ])->label(false); ?>
                    </div>
                </div>
                <!-- /.field-row -->

                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="phone_number">Số điện thoại</label>
                    <div class="col-sm-10">
                        <?= $form->field($modelGuest, 'phone_number', [
                            'inputOptions' => [
                                'class' => 'form-control',
                                'maxlength' => 15,
                                'id'=>'phone_number',
                                'placeholder' => 'Tên đăng nhập',
                            ],
                        ])->label(false); ?>
                    </div>
                </div>
                <!-- /.field-row -->
            </fieldset>
            <div class="buttons">
                <div class="pull-right">I have read and agree to the <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/information/agree&amp;information_id=3" class="agree"><b>Privacy Policy</b></a>                        <input type="checkbox" name="agree" value="1" />
                    &nbsp;
                    <button type="submit" class="btn btn-primary" name="submit">Đăng ký</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
