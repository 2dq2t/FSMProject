<?php
/**
 * Created by PhpStorm.
 * User: TuDA
 * Date: 7/9/2015
 * Time: 3:26 PM
 */
?>
<column id="column-left" class="col-sm-3 hidden-xs">
    <div class="box">
        <div class="box-heading"><?=Yii::t('app','AccountLabel')?></div>
        <div class="list-group">
            <a href="../web/index.php?r=account/register"
               class="list-group-item"><?=Yii::t('app','RegisterLabel')?></a>
            <a href="../web/index.php?r=account/login"
               class="list-group-item"><?=Yii::t('app','LoginLabel')?></a>
            <a href="../web/index.php?r=account/request-password-reset"
               class="list-group-item"><?=Yii::t('app','ForgottenPasswordLabel')?></a>
        </div>
    </div>
</column>