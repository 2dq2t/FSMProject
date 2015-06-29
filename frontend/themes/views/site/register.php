<?php

use kartik\widgets\ActiveForm;
$this->title = 'Đăng ký';

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
                <li><a href="#"><i
                            class="fa fa-home"></i></a></li>
                <li><a href="#">Tài Khoản</a></li>
                <li><a href="#">Đăng Ký</a>
                </li>
            </ul>
            <h2>Đăng Ký Tài Khoản</h2>

            <p>Hãy đăng ký để trở thành khách hàng của Fresh Garden</p>
            <?php $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL,
                'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset id="information">
                <legend>Thông tin cá nhân</legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelGuest, 'full_name',[
                            'showLabels'=>true
                        ])->textInput(['placeholder'=> $modelGuest->getAttributeLabel('full_name')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelGuest, 'email',[
                            'showLabels'=>true
                        ])->textInput(['placeholder'=> $modelGuest->getAttributeLabel('email')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelGuest, 'phone_number',[
                            'showLabels'=>true
                        ])->textInput(['placeholder'=> $modelGuest->getAttributeLabel('phone_number')]); ?>
                    </div>
                </div>
            </fieldset>
            <fieldset id="password" class="register">
                <legend>Tài khoản</legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelCustomer, 'username',[
                            'showLabels'=>true
                        ])->textInput(['placeholder'=> $modelCustomer->getAttributeLabel('username')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelCustomer, 'password',[
                            'showLabels'=>true
                        ])->passwordInput(['placeholder'=> $modelCustomer->getAttributeLabel('password')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelCustomer, 're_password',[
                            'showLabels'=>true
                        ])->passwordInput(['placeholder'=> $modelCustomer->getAttributeLabel('re_password')]); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons">
                <div class="pull-right">Tôi đã đọc và đồng ý với điều khoản sử dụng của <a href="#" class="agree"><b>Fresh Garden</b></a>
                    &nbsp;
                    <input type="submit" value="Đăng Ký" class="btn btn-primary" />
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
