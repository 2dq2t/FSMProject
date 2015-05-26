<?php

use kartik\widgets\ActiveForm;
$this->title = 'Quên mật khẩu';

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
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/forgotten">Forgotten Password</a></li>
            </ul>

            <h1 class="page-title">Forgot Your Password?</h1>
            <p style="margin-bottom: 10px;">Enter the e-mail address associated with your account. Click submit to have your password e-mailed to you.</p>

            <?php $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL,
                'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset id="information">
                <legend>Địa chỉ Email</legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-fullname">Email</label>
                    <div class="col-sm-10">
                        <?= $form->field($model, 'email',[
                            'showLabels'=>false
                        ])->textInput(['placeholder'=>'Email']); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons clearfix">
                <div class="pull-left"><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/login" class="btn btn-default">Quay lại</a></div>
                <div class="pull-right">
                    <input type="submit" value="Gửi" class="btn btn-primary" />
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
