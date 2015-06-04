<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\alert\Alert;
use kartik\widgets\DepDrop;
use common\models\City;
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

<div class="container content-inner">
    <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
        <?php
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : Yii::t('app', 'Message Not Set!'),
            'showSeparator' => true,
            'delay' => 3000
        ]);
        ?>
    <?php endforeach; ?>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <div class="box">
                <div class="box-heading">Tài Khoản</div>
                <div class="list-group">
                    <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Tài Khoản Của Tôi</a>
                    <a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Thay Đổi Tài Khoản</a>
                    <a href="index.php?r=customer/changepass&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Mật Khẩu</a>
                    <a href="index.php?r=customer/changeaddress&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Thay Đổi Địa Chỉ</a>
                    <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/logout" class="list-group-item">Thoát</a>
                </div>
            </div>
        </column>
        <ul class="breadcrumb">
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i class="fa fa-home"></i></a></li>
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account">Tài Khoản</a></li>
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/password">Thay Đổi Địa Chỉ</a></li>
        </ul>
        <div id="content" class="col-sm-9">      <h1 class="page-title">Địa chỉ của tôi</h1>
            <?php $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL,
                'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset>
                <legend>Thông tin địa chỉ</legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname">Địa Chỉ</label>
                    <div class="col-sm-10">
                        <?php
                            if(isset($modelUpdateAddress)){
                                echo $form->field($modelUpdateAddress, 'detail',[
                                    'showLabels'=>false
                                ])->textInput();
                            }else{
                                echo $form->field($modelAddress, 'detail',[
                                    'showLabels'=>false
                                ])->textInput();
                            }
                        ?>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname">Tỉnh / Thành Phố</label>
                    <div class="col-sm-10">
                        <?php
                            if (isset($modelUpdateAddress)) {
                                echo $form->field($modelUpdateCity, 'id')->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                    [
                                        'prompt'=> Yii::t('app', '- Chọn Tỉnh / Thành phố -'),
                                        'onchange'=>'
                                                $.post( "index.php?r=customer/getdistrict&id="+$(this).val(), function( file ) {
                                                    $( "select#district-id" ).length = 0;
                                                    $( "select#district-id" ).html( file );
                                                    var event = new Event("change");
                                                    document.getElementById("district-id").dispatchEvent(event);
                                                });'
                                    ])->label(false);
                            } else {
                                echo $form->field($modelCity, 'id')->dropDownList(
                                        \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                        ['prompt'=>Yii::t('app', '- Chọn Tỉnh / Thành phố -')])->label(false);
                            }
                        ?>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname">Quận / Huyện</label>
                    <div class="col-sm-10">
                        <?php
                            if (isset($modelUpdateAddress)) {
                                echo $form->field($modelUpdateAddress, 'district_id')->dropDownList(
                                    \yii\helpers\ArrayHelper::map(
                                        \common\models\District::find()
                                            ->where(['city_id' => $modelUpdateCity->id])
                                            ->all(), 'id', 'name'),
                                    [
                                        'prompt'=>Yii::t('app', '- Chọn Quận / Huyện -'),
                                    ]
                                )->label(false);
                            } else {
                                echo $form->field($modelAddress, 'district_id')->widget(\kartik\widgets\DepDrop::classname(), [
                                    'options'=>['prompt' => Yii::t('app', '- Chọn Quận / Huyện -')],
                                    'pluginOptions'=>[
                                        'depends'=>[Html::getInputId($modelCity, 'id')],
                                        'placeholder'=>Yii::t('app', '- Chọn Quận / Huyện -'),
                                        'url'=>\yii\helpers\Url::to(['/customer/getdistrict'])
                                    ]
                                ])->label(false);
                            }
                        ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons clearfix">
                <div class="pull-left"><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account" class="btn btn-default">Quay lại</a></div>
                <div class="pull-right">
                    <input type="submit" value="Lưu" class="btn btn-primary" />
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>