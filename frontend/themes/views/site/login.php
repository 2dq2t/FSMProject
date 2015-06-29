<?php

use yii\bootstrap\ActiveForm;
$this->title = 'Đăng nhập';

?>
<?php require('_header.php');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <div class="box">
                <div class="box-heading">Tài Khoản</div>
                <div class="list-group">
                    <a href="#"
                       class="list-group-item">Đăng Nhập</a>
                    <a href="#"
                       class="list-group-item">Đăng Ký</a>
                    <a href="#"
                       class="list-group-item">Quên Mật Khẩu</a>
                    <a href="#"
                       class="list-group-item">Yêu Thích</a>
                </div>
            </div>
        </column>
        <div id="content" class="col-sm-9">
            <ul class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i></a></li>
                <li><a href="#">Tài Khoản</a></li>
                <li><a href="#">Đăng Nhập</a></li>
            </ul>

            <div class="row">
                <div class="col-sm-6 register">
                    <div class="well">
                        <h2>Khách Hàng Mới</h2>
                        <p><strong>Đăng Ký Tài Khoản</strong></p>
                        <p>Bằng cách tạo một tài khoản bạn sẽ có thể mua sắm tiện lợi hơn.
                        Hãy trở thành một khách hàng của Fresh Garden để nhận được những ưu đãi tốt nhất.</p>
                        <a href="index.php?r=site/register" class="btn btn-primary">Tiếp Tục</a>
                    </div>
                </div>
                <div class="col-sm-6 login">
                    <div class="well">
                        <h2>Khách Hàng</h2>
                        <p><strong>Tôi là một khách hàng của Fresh Garden</strong></p>
                        <?php $form = ActiveForm::begin(['id' => 'form-login', 'method' => 'post']); ?>
                        <div class="form-group">
                            <label class="control-label" for="input-email"><?= $model->generateAttributeLabel('username') ?></label>
                            <?= $form->field($model, 'username')
                                ->label(false)->textInput(['placeholder'=> $model->generateAttributeLabel('username') ]); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="input-password"><?= $model->generateAttributeLabel('password') ?></label>
                            <?= $form->field($model, 'password')
                                ->label(false)->passwordInput(['placeholder'=> $model->generateAttributeLabel('password')]); ?>
                            <div class="forget-password"><a href="index.php?r=site/request-password-reset">Quên Mật Khẩu?</a></div>
                        </div>
                        <input type="submit" value="Đăng Nhập" class="btn btn-primary" />
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
