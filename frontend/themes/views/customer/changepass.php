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
                <div class="box-heading">Account</div>
                <div class="list-group">
                    <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">My Account</a>
                    <a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Edit Account</a>
                    <a href="index.php?r=customer/changepass&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item">Password</a>
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
        <ul class="breadcrumb">
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i class="fa fa-home"></i></a></li>
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/account">Account</a></li>
            <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=account/password">Change Password</a></li>
        </ul>
        <div id="content" class="col-sm-9">      <h1 class="page-title">Change Password</h1>
            <?php $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL,
                'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset>
                <legend>Your Password</legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname">Mật Khẩu Mới</label>
                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'new_password',[
                            'showLabels'=>false
                        ])->passwordInput(['placeholder'=>'Mật Khẩu Mới']); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname">Xác Nhận Mật Khẩu</label>
                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 're_new_password',[
                            'showLabels'=>false
                        ])->passwordInput(['placeholder'=>'Xác Nhận Mật Khẩu']); ?>
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