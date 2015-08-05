<?php
/**
 * Created by PhpStorm.
 * User: TuDA
 * Date: 7/18/2015
 * Time: 9:40 PM
 */
?>
<column id="column-left" class="col-sm-3 hidden-xs">
    <div class="box">
        <div class="box-heading"><?= Yii::t('app', 'AccountLabel') ?></div>
        <div class="list-group">
            <a href="../site/index.php?r=site/login"
               class="list-group-item"><?= Yii::t('app', 'LoginLabel') ?>
            </a>
            <a href="../site/index.php?r=site/register"
               class="list-group-item"><?= Yii::t('app', 'RegisterLabel') ?>
            </a>
            <a href="../site/index.php?r=site/request-password-reset"
               class="list-group-item"><?= Yii::t('app', 'ForgottenPasswordLabel') ?>
            </a>
        </div>
    </div>
</column>