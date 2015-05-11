<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="row">
    <div id="top-banner-and-menu">
        <?php
        Yii::$app->session->getFlash('success');
        ?>
        <div class="container">
            <!--BEGIN NAVBAR-->
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
            </div><!--END NAVBAR-->

            <!--BEGIN CONTENT-->
            <div class="col-md-9">
                <fieldset class="scheduler-border">
                    <?php $form = ActiveForm::begin(['id' => 'form-manage-acc', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1', 'enctype'=>'multipart/form-data']]); ?>

                    <div class="field-row">
                        <label>Mật khẩu mới</label>
                        <?= $form->field($modelCustomer, 'new_password', [
                            'inputOptions' => [
                                'class' => 'le-input',
                                'maxlength' => 100,
                            ],
                        ])->passwordInput()->label(false); ?>
                    </div><!-- /.field-row -->

                    <div class="field-row">
                        <label>Xác nhận Mật khẩu</label>
                        <?= $form->field($modelCustomer, 're_new_password', [
                            'inputOptions' => [
                                'class' => 'le-input',
                                'maxlength' => 100,
                            ],
                        ])->passwordInput()->label(false); ?>
                    </div><!-- /.field-row -->

                    <div class="buttons-holder">
                        <button type="submit" class="le-button huge" name="submit">Lưu thông tin</button>
                    </div><!-- /.buttons-holder -->

                    <?php ActiveForm::end(); ?>
                </fieldset>
            </div><!--END CONTENT-->
        </div>
    </div>
</div>
<section id="top-brands" class="wow fadeInUp">
</section>
