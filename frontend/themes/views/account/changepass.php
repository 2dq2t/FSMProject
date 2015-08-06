<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\alert\Alert;
$this->title = Yii::t('app', 'ChangePassInfoLabel');
require('../themes/views/layouts/_header.php');
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
        <?php require('_leftMenu.php'); ?>
        <ul class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-home"></i></a></li>
            <li><a href="index.php?r=account/manageacc&id=<?= Yii::$app->user->identity->id; ?>"><?= Yii::t('app', 'AccountLabel') ?></a></li>
            <li><a href="index.php?r=account/changepass&id=<?= Yii::$app->user->identity->id;?>"><?= Yii::t('app', 'ChangePassInfoLabel') ?></a></li>
        </ul>
        <div id="content" class="col-sm-9">
            <?php $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL,
                'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset>
                <legend><?=Yii::t('app','PasswordLabel')?></legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname"><?= Yii::t('app','Customer New Password') ?></label>
                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'new_password',[
                            'showLabels'=>false
                        ])->passwordInput(['placeholder'=> Yii::t('app','Customer New Password')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname"><?= Yii::t('app','Customer Re_Confirm Password') ?></label>
                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 're_new_password',[
                            'showLabels'=>false
                        ])->passwordInput(['placeholder'=> Yii::t('app','Customer Re_Confirm Password')]); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons clearfix">
                <div class="pull-left"><a href="index.php?r=account/manageacc&id=<?= Yii::$app->user->identity->id; ?>" class="btn btn-default"><?= Yii::t('app', 'BackLabel') ?></a></div>
                <div class="pull-right">
                    <input type="submit" value="<?= Yii::t('app', 'SaveLabel') ?>" class="btn btn-primary" />
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>