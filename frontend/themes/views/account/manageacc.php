<?php

$this->title = Yii::t('app','MyAccountLabel');
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php
require('../themes/views/layouts/_header.php');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <?php require('_leftMenu.php'); ?>
        <ul class="breadcrumb">
            <li><a href="index.php"><i
                        class="fa fa-home"></i></a></li>
            <li><a href="index.php?r=account/manageacc&id=<?= Yii::$app->user->identity->id;?>"><?=Yii::t('app','MyAccountLabel')?></a></li>
        </ul>
        <div id="content" class="col-sm-9">
            <h2 class="h2-account" style="margin-bottom: 5px;"><?=Yii::t('app','MyAccountLabel')?></h2>
            <ul class="list-unstyled-account">
                <li><a href="index.php?r=account/update&id=<?= Yii::$app->user->identity->id; ?>"><?=Yii::t('app','ChangeAccInfoLabel')?></a></li>
                <li><a href="index.php?r=account/changepass&id=<?= Yii::$app->user->identity->id; ?>"><?=Yii::t('app','ChangePassInfoLabel')?></a></li>
                <li><a href="index.php?r=account/changeaddress&id=<?= Yii::$app->user->identity->id; ?>"><?=Yii::t('app','ChangeAddressInfoLabel')?></a></li>
                <li><a href="index.php?r=wish-list/get-wish-list"><?=Yii::t('app','WishListLabel')?></a></li>
                <li><a href="index.php?r=account/get-order-history"><?=Yii::t('app','OrderHistoryLabel')?></a></li>
            </ul>
        </div>
    </div>
</div>
