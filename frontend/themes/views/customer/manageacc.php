<?php

use kartik\widgets\ActiveForm;
$this->title = 'Tài khoản của tôi';

?>
<div class="container content-inner">

    <div class="row content-subinner">
        <ul class="breadcrumb">
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i class="fa fa-home"></i></a></li>
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account">Account</a></li>
        </ul>
        <column id="column-left" class="col-sm-3 hidden-xs">
            <div class="box">
                <div class="box-heading">Account</div>
                <div class="list-group">
                    <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">My Account</a>
                    <a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Edit Account</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/password" class="list-group-item">Password</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/address" class="list-group-item">Address Books</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/wishlist" class="list-group-item">Wish List</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/order" class="list-group-item">Order History</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/download" class="list-group-item">Downloads</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/reward" class="list-group-item">Reward Points</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/return" class="list-group-item">Returns</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/transaction" class="list-group-item">Transactions</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/newsletter" class="list-group-item">Newsletter</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/recurring" class="list-group-item">Recurring payments</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/logout" class="list-group-item">Logout</a>
                </div>
            </div>
        </column>
        <div id="content" class="col-sm-9">	  <h1 class="page-title">My Account</h1>
            <h2 class="h2-account" style="margin-bottom: 5px;">My Account</h2>
            <ul class="list-unstyled-account">
                <li><a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id;?>">Edit your account information</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/password">Change your password</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/address">Modify your address book entries</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/wishlist">Modify your wish list</a></li>
            </ul>
            <h2 class="h2-account" style="margin-bottom: 5px;">My Orders</h2>
            <ul class="list-unstyled-account">
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/order">View your order history</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/download">Downloads</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/reward">Your Reward Points</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/return">View your return requests</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/transaction">Your Transactions</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/recurring">Recurring payments</a></li>
            </ul>
            <h2 class="h2-account" style="margin-bottom: 5px;">Newsletter</h2>
            <ul class="list-unstyled-account">
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/newsletter">Subscribe / unsubscribe to newsletter</a></li>
            </ul>
        </div>
    </div>
</div>
