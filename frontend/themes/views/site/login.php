<?php

use yii\bootstrap\ActiveForm;
$this->title = 'Đăng nhập';

?>
<?php echo $this->render('_navbar',[
    'modelCategory' => $modelCategory,
]);
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
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i class="fa fa-home"></i></a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account">Account</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/login">Login</a></li>
            </ul>

            <div class="row">
                <div class="col-sm-6 register">
                    <div class="well">
                        <h2>New Customer</h2>
                        <p><strong>Register Account</strong></p>
                        <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
                        <a href="index.php?r=site/register" class="btn btn-primary">Continue</a>
                    </div>
                </div>
                <div class="col-sm-6 login">
                    <div class="well">
                        <h2>Returning Customer</h2>
                        <p><strong>I am a returning customer</strong></p>
                        <?php $form = ActiveForm::begin(['id' => 'form-login', 'method' => 'post']); ?>
                        <div class="form-group">
                            <label class="control-label" for="input-email">Tên Đăng Nhập</label>
                            <?= $form->field($model, 'username',[
                                'inputOptions' => [
                                    'maxlength' => 255,
                                ]
                            ])->label(false)->textInput(['placeholder'=>'Tên Đăng Nhập']); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="input-password">Mật Khẩu</label>
                            <?= $form->field($model, 'password',[
                                'inputOptions' => [
                                    'maxlength' => 255,
                                ]
                            ])->label(false)->passwordInput(['placeholder'=>'Mật Khẩu']); ?>
                            <div class="forget-password"><a href="index.php?r=site/request-password-reset">Forgotten Password</a></div>
                        </div>
                        <input type="submit" value="Đăng Nhập" class="btn btn-primary" />
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
