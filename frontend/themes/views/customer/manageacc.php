<?php

use kartik\widgets\ActiveForm;
$this->title = 'Tài khoản của tôi';

?>
<div class="container content-inner">

    <div class="row content-subinner">
        <ul class="breadcrumb">
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i class="fa fa-home"></i></a></li>
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account">Tài Khoản</a></li>
        </ul>
        <column id="column-left" class="col-sm-3 hidden-xs">
            <div class="box">
                <div class="box-heading">Tài khoản</div>
                <div class="list-group">
                    <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Tài Khoản Của Tôi</a>
                    <a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Thay Đổi Tài Khoản</a>
                    <a href="index.php?r=customer/changepass&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Mật Khẩu</a>
                    <a href="index.php?r=customer/changeaddress&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Thay Đổi Địa Chỉ</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/logout" class="list-group-item">Thoát</a>
                </div>
            </div>
        </column>
        <div id="content" class="col-sm-9">	  <h1 class="page-title">Tài Khoản Của Tôi</h1>
            <h2 class="h2-account" style="margin-bottom: 5px;">Tài Khoản Của Tôi</h2>
            <ul class="list-unstyled-account">
                <li><a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id;?>">Thay đổi thông tin tài khoản</a></li>
                <li><a href="index.php?r=customer/changepass&id=<?= Yii::$app->user->identity->id;?>">Thay đổi mật khẩu</a></li>
                <li><a href="index.php?r=customer/changeaddress&id=<?= Yii::$app->user->identity->id;?>">Thay đổi địa chỉ</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/wishlist">Thay đổi danh mục yêu thích</a></li>
            </ul>
            <h2 class="h2-account" style="margin-bottom: 5px;">Đơn Hàng Của Tôi</h2>
            <ul class="list-unstyled-account">
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/order">Lịch sử mua hàng</a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/download">Tải về</a></li>
            </ul>
            <h2 class="h2-account" style="margin-bottom: 5px;">Nhận Thông Báo</h2>
            <ul class="list-unstyled-account">
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/newsletter">Theo dõi / Không theo dõi thông báo</a></li>
            </ul>
        </div>
    </div>
</div>
