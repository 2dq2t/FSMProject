<?php

use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'ForgottenPasswordLabel');
require('_header.php');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <?php require('_leftMenuChangePass.php'); ?>
        <div id="content" class="col-sm-9">
            <ul class="breadcrumb">
                <li><a href="index.php"><i
                            class="fa fa-home"></i></a></li>
                <li><a href="index.php?r=customer/manageacc"><?= Yii::t('app', 'AccountLabel') ?></a></li>
                <li><a href="index.php?r=site/request-password-reset"><?= Yii::t('app', 'ForgottenPasswordLabel') ?></a>
                </li>
            </ul>

            <h1 class="page-title"><?= Yii::t('app', 'ForgottenPasswordLabel') ?>?</h1>

            <p style="margin-bottom: 10px;"><?= Yii::t('app', 'ForgottenPasswordNotice01') ?></p>

            <?php $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset id="information">
                <legend><?= Yii::t('app', 'EmailAddressLabel') ?></legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-fullname"><?= $model->generateAttributeLabel('email') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($model, 'email', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => $model->generateAttributeLabel('email')]); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons clearfix">
                <div class="pull-left"><a href="index.php?r=site/login"
                                          class="btn btn-default"><?= Yii::t('app', 'BackLabel') ?></a></div>
                <div class="pull-right">
                    <input type="submit" value="<?= Yii::t('app', 'SendLabel') ?>" class="btn btn-primary"/>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
