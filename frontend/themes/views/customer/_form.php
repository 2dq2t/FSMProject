<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\City;
use kartik\widgets\DepDrop;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="top-banner-and-menu">
    <div class="container">
        <div class="col-xs-12 col-sm-4 col-md-3 sidemenu-holder">
            <div class="side-menu animate-dropdown">
                <div class="head"><i class="fa fa-list"></i> Tài khoản của bạn</div>
                <nav class="yamm megamenu-horizontal" role="navigation">
                    <ul class="nav">
                        <li class="dropdown menu-item">
                            <a href="#" class="">Quản lý tài khoản</a>
                        </li><!-- /.menu-item -->
                        <li class="dropdown menu-item">
                            <a href="#" class="" >Thông tin tài khoản</a>
                        </li><!-- /.menu-item -->
                        <li class="dropdown menu-item">
                            <a href="#" class="" >Đơn hàng của tôi</a>
                        </li><!-- /.menu-item -->
                        <li class="dropdown menu-item">
                            <a href="#" class="" >Sản phẩm yêu thích</a>
                        </li><!-- /.menu-item -->
                        <li class="dropdown menu-item">
                            <a href="#" class="" >Mã khách hàng của tôi</a>
                        </li><!-- /.menu-item -->
                        <li class="dropdown menu-item">
                            <a href="#" class="" >Thoát</a>
                        </li><!-- /.menu-item -->
                    </ul>
                </nav>
            </div>
        </div>

        <div class="row">
        <div class="col-md-9">
            <fieldset class="scheduler-border">
                <?php $form = ActiveForm::begin(['id' => 'form-manage-acc', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>

                <div class="field-row">
                    <label>Tên đăng nhập</label>
                    <?= $form->field($model, 'username', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Mật khẩu</label>
                    <span style="padding: 9px;">*****</span>
                    <a href="#" style="float: right; padding-right: 9px;">Đổi mật khẩu</a>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Họ và tên</label>
                    <?= $form->field($modelGuest, 'full_name', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Email</label>
                    <?= $form->field($modelGuest, 'email', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Giới tính</label>
                    <?= $form->field($model, 'gender')->dropDownList(
                        ['Male' => 'Nam', 'Female' => 'Nữ', 'Other' => 'Khác'],
                        ['class'=>'le-select-address'])
                        ->label(false);
                    ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Ngày sinh</label>
                    <?= $form->field($model, 'dob')->widget(DatePicker::className(),
                        ['name' => 'dob',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => $model->dob,
                            'options' => [
                                'class' => 'le-input',
                            ],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ],
                        ])->label(false) ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Số nhà / đường phố, thôn xóm</label>
                    <?= $form->field($modelAddress, 'detail', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 100,
                        ],
                    ])->label(false); ?>
                </div><!-- /.field-row -->


                <div class="field-row">
                    <label>Tỉnh / Thành Phố</label>
                    <?= $form->field($modelCity, 'name')->dropDownList(City::getCity(),
                        ['id' => 'city-id', 'class'=>'le-select-address','prompt' => $modelCity->name])
                        ->label(false);
                    ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Quận / Huyện</label>
                    <?= $form->field($modelDistrict, 'name')->widget(DepDrop::classname(), [
                        'options'=>['id'=>'district-id', 'class'=>'le-select-address','prompt' => $modelDistrict->name],
                        'pluginOptions'=>[
                            'depends'=>['city-id'],
                            'placeholder'=>'- Chọn Quận / Huyện -',
                            'url'=>\yii\helpers\Url::to(['/site/subcat'])
                        ]
                    ])->label(false); ?>
                </div><!-- /.field-row -->

                <div class="field-row">
                    <label>Xã / Phường</label>
                    <?= $form->field($modelAddress, 'ward_id')->widget(DepDrop::classname(), [
                        'options'=>['id'=>'ward-id', 'class'=>'le-select-address','prompt' => $modelWard->name],
                        'pluginOptions'=>[
                            'depends'=>['city-id','district-id'],
                            'placeholder'=>'- Chọn Xã / Phường -',
                            'url'=>\yii\helpers\Url::to(['/site/prod'])
                        ]
                    ])->label(false); ?>
                </div><!-- /.field-row -->

                <div class="buttons-holder">
                    <button type="submit" class="le-button huge" name="submit">Lưu thông tin</button>
                </div><!-- /.buttons-holder -->

                <?php ActiveForm::end(); ?>
            </fieldset>
        </div>

    </div>
        </div>
</div>
<section id="top-brands" class="wow fadeInUp">
</section>