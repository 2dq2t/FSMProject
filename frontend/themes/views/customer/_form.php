<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\alert\Alert;
/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>
<?php

$user_avatar = '';

if(isset($modelCustomer->avatar)) {
    $user_avatar = Html::img('../../frontend/web/uploads/users/avatar/'. $modelCustomer->id .'/'. $modelCustomer->avatar, ['class' => 'file-preview-image']);
}

?>



<div class="row">
    <div id="top-banner-and-menu">
        <div class="container">
            <?php if(Yii::$app->session->hasFlash('successful')): ?>
                <div id="w0-success-message" class="alert-success alert fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?= Yii::$app->session->getFlash('successful') ?>
                </div>
            <?php endif; ?>
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
            <div class="col-xs-12 col-sm-8 col-md-9 homebanner-holder">
                <fieldset class="scheduler-border">
                    <?php $form = ActiveForm::begin(['id' => 'form-manage-acc', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1', 'enctype'=>'multipart/form-data']]); ?>

                    <div class="field-row">
                        <label>Avatar</label>
                        <?= $form->field($modelCustomer, 'avatar')->widget(\kartik\widgets\FileInput::className(), [
                            'options' => [
                                'accept' => 'image/*'
                            ],
                            'pluginOptions' => [
                                'showCaption' => true,
                                'allowedFileExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
                                'showUpload' => false,
                                'initialPreview' => $user_avatar,
                                'overwriteInitial' => true,
                                'layoutTemplates' => [
                                    'footer' => '<div class="file-thumbnail-footer">'.
                                        '<div class="file-caption-name">{caption}</div>'.
                                        '<div class="file-actions">'.
                                        '<div class="file-footer-buttons">'.
                                        '<button type="button" class="kv-file-remove btn btn-xs btn-default" title="Remove file">'.
                                        '<i class="glyphicon glyphicon-trash text-danger"></i>'.
                                        '</button>'.
                                        '</div>'.
                                        '</div>'.
                                        '</div>'
                                ]
                            ]
                        ])->label(false) ?>
                    </div>

                    <div class="field-row">
                        <label>Tên đăng nhập</label>
                        <?= $form->field($modelCustomer, 'username', [
                            'inputOptions' => [
                                'class' => 'le-input',
                                'maxlength' => 255,
                            ],
                        ])->label(false); ?>
                    </div><!-- /.field-row -->

                    <div class="field-row">
                        <label>Mật khẩu</label>
                        <span style="padding: 9px;">*****</span>
                        <?php
                        echo "<a href='index.php?r=customer/changepass&id=".Yii::$app->user->identity->id."' style='float: right; padding-right: 9px;'>Đổi mật khẩu</a>"
                        ?>
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
                        <?= $form->field($modelCustomer, 'gender')->dropDownList(
                            ['Male' => 'Nam', 'Female' => 'Nữ', 'Other' => 'Khác'],
                            ['class'=>'le-select-address'])
                            ->label(false);
                        ?>
                    </div><!-- /.field-row -->

                    <div class="field-row">
                        <label>Ngày sinh</label>
                        <?= $form->field($modelCustomer, 'dob')->widget(DatePicker::className(),
                            [
                                'removeButton' => false,
                                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                'language' => 'vi',
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'endDate' => '+0d',
                                    'setDate' => date('yyyy-mm-dd'),
                                    'format' => 'yyyy-mm-dd',
                                    'todayHighlight' => true
                                ],
                                'options' => ['class' => 'le-input'],
                            ])->label(false); ?>
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
                        <?php
                        echo $form->field($modelCity, 'id')
                            ->dropDownList(
                                \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                [
                                    'prompt'=>'- Chọn Tỉnh / Thành phố -',
                                    'class'=>'le-select-address',
                                    'onchange'=>'
                                                    $.post( "index.php?r=customer/getdistrict&id="+$(this).val(), function( data ) {
                                                        $( "select#district-id" ).length = 0;
                                                        $( "select#district-id" ).html( data );
                                                    });'
                                ])->label(false);
                        ?>
                    </div><!-- /.field-row -->

                    <div class="field-row">
                        <label>Quận / Huyện</label>
                        <?php
                        echo $form->field($modelDistrict, 'id')->dropDownList(
                            \yii\helpers\ArrayHelper::map(
                                \common\models\District::find()
                                    ->where(['city_id' => $modelCity->id])
                                    ->all(), 'id', 'name'),
                            [
                                'prompt'=>'- Chọn Quận / Huyện -',
                                'class'=>'le-select-address',
                                'onchange'=>'
                                                    $.post( "index.php?r=customer/getward&id="+$(this).val(), function( data ) {
                                                        $( "select#address-ward_id" ).length = 0;
                                                        $( "select#address-ward_id" ).html( data );
                                                    });'
                            ]
                        )->label(false);
                        ?>
                    </div><!-- /.field-row -->

                    <div class="field-row">
                        <label>Xã / Phường</label>
                        <?php
                        echo $form->field($modelAddress, 'ward_id')->dropDownList(
                            \yii\helpers\ArrayHelper::map(
                                \common\models\Ward::find()
                                    ->where(['district_id' => $modelDistrict->id])
                                    ->all(), 'id', 'name'),
                            ['class'=>'le-select-address','prompt'=>'- Chọn Xã / Phường -']
                        )->label(false);
                        ?>
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