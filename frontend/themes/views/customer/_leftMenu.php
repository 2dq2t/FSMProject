<?php
/**
 * Created by PhpStorm.
 * User: TuDA
 * Date: 7/17/2015
 * Time: 11:19 PM
 */
?>
<column id="column-left" class="col-sm-3 hidden-xs">
    <div class="box">
        <div class="box-heading"><?=Yii::t('app','AccountLabel')?></div>
        <div class="list-group">
            <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item"><?=Yii::t('app','MyAccountLabel')?></a>
            <a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item"><?=Yii::t('app','ChangeAccInfoLabel')?></a>
            <a href="index.php?r=customer/changepass&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item"><?=Yii::t('app','ChangePassInfoLabel')?></a>
            <a href="index.php?r=customer/changeaddress&id=<?= Yii::$app->user->identity->id;?>" class="list-group-item"><?=Yii::t('app','ChangeAddressInfoLabel')?></a>
            <a href="index.php?r=site/logout" class="list-group-item" data-method="post"><?=Yii::t('app','LogoutLabel')?></a>
        </div>
    </div>
</column>