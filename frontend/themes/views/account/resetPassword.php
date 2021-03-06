<?php

use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'ChangePassInfoLabel');
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <?php echo $this->render('/layouts/_leftMenuChangePass.php'); ?>
        <div id="content" class="col-sm-9">
            <ul class="breadcrumb">
                <li><a href="../site/index.php"><i class="fa fa-home"></i></a></li>
                <li><a href="../site/index.php?r=customer/manageacc"><?= Yii::t('app', 'AccountLabel') ?></a></li>
                <li><a href="#"><?= Yii::t('app', 'ChangePassInfoLabel') ?></a></li>
            </ul>

            <h1 class="page-title"><?= Yii::t('app', 'ChangePassInfoLabel') ?>?</h1>

            <p style="margin-bottom: 10px;"><?= Yii::t('app', 'ForgottenPasswordNotice02') ?></p>

            <?php $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset id="information">
                <legend><?= Yii::t('app', 'PasswordLabel') ?></legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-fullname"><?= Yii::t('app','PasswordLabel') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($model, 'password', [
                            'showLabels' => false
                        ])->passwordInput(['placeholder' => Yii::t('app','PasswordLabel')]); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons clearfix">
                <div class="pull-right">
                    <input type="submit" value="<?= Yii::t('app', 'SaveLabel') ?>" class="btn btn-primary"/>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
