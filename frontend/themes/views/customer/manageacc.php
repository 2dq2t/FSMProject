<?php

use kartik\widgets\ActiveForm;

$this->title = Yii::t('app','MyAccountLabel');
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php
require('../themes/views/site/_header.php');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <?php require('_leftMenu.php'); ?>
        <ul class="breadcrumb">
            <li><a href="index.php"><i
                        class="fa fa-home"></i></a></li>
            <li><a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id;?>"><?=Yii::t('app','MyAccountLabel')?></a></li>
        </ul>
        <div id="content" class="col-sm-9">
            <h2 class="h2-account" style="margin-bottom: 5px;"><?=Yii::t('app','MyAccountLabel')?></h2>
            <ul class="list-unstyled-account">
                <li><a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id; ?>"><?=Yii::t('app','ChangeAccInfoLabel')?></a></li>
                <li><a href="index.php?r=customer/changepass&id=<?= Yii::$app->user->identity->id; ?>"><?=Yii::t('app','ChangePassInfoLabel')?></a></li>
                <li><a href="index.php?r=customer/changeaddress&id=<?= Yii::$app->user->identity->id; ?>"><?=Yii::t('app','ChangeAddressInfoLabel')?></a></li>
                <li><a href="index.php?r=site/wish-list"><?=Yii::t('app','WishListLabel')?></a></li>
            </ul>
            <h2 class="h2-account" style="margin-bottom: 5px;"><?=Yii::t('app','MyOrderLabel')?></h2>
            <ul class="list-unstyled-account">
                <li><a href="#"><?=Yii::t('app','OrderHistoryLabel')?></a></li>
                <li><a href="#"><?=Yii::t('app','DownloadLabel')?></a></li>
            </ul>
        </div>
    </div>
</div>
