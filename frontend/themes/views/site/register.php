<?php

use kartik\widgets\ActiveForm;
$this->title = 'Đăng kí';

?>
<?php require('_navBar.php');
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
            <?php $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL,
                'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset id="information">
                <legend>Thông tin cá nhân</legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-3 control-label" for="input-fullname">Họ và tên</label>
                    <div class="col-sm-9">
                        <?= $form->field($modelGuest, 'full_name',[
                            'showLabels'=>false
                        ])->textInput(['placeholder'=>'Họ và tên']); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-3 control-label" for="input-fullname">Email</label>
                    <div class="col-sm-9">
                        <?= $form->field($modelGuest, 'email',[
                            'showLabels'=>false
                        ])->textInput(['placeholder'=>'Email']); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-3 control-label" for="input-fullname">Số điện thoại</label>
                    <div class="col-sm-9">
                        <?= $form->field($modelGuest, 'phone_number',[
                            'showLabels'=>false
                        ])->textInput(['placeholder'=>'Số điện thoại']); ?>
                    </div>
                </div>
            </fieldset>
            <fieldset id="password" class="register">
                <legend>Tài khoản</legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-3 control-label" for="input-fullname">Tên Đăng Nhập</label>
                    <div class="col-sm-9">
                        <?= $form->field($modelCustomer, 'username',[
                            'showLabels'=>false
                        ])->textInput(['placeholder'=>'Tên Đăng Nhập']); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-3 control-label" for="input-fullname">Mật Khẩu</label>
                    <div class="col-sm-9">
                        <?= $form->field($modelCustomer, 'password',[
                            'showLabels'=>false
                        ])->passwordInput(['placeholder'=>'Mật Khẩu']); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-3 control-label" for="input-fullname">Xác Nhận Mật Khẩu</label>
                    <div class="col-sm-9">
                        <?= $form->field($modelCustomer, 're_password',[
                            'showLabels'=>false
                        ])->passwordInput(['placeholder'=>'Xác Nhận Mật Khẩu']); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons">
                <div class="pull-right">I have read and agree to the <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=information/information/agree&amp;information_id=3" class="agree"><b>Privacy Policy</b></a>                        <input type="checkbox" name="agree" value="1" />
                    &nbsp;
                    <input type="submit" value="Đăng ký" class="btn btn-primary" />
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
