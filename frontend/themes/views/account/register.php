<?php

use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'RegisterLabel');
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <?php echo $this->render('/layouts/_leftMenuForgetPassword.php'); ?>
        <div id="content" class="col-sm-9">
            <ul class="breadcrumb">
                <li><a href="#"><i
                            class="fa fa-home"></i></a></li>
                <li><a href="#"><?= Yii::t('app', 'AccountLabel') ?></a></li>
                <li><a href="#"><?= Yii::t('app', 'RegisterLabel') ?></a>
                </li>
            </ul>
            <h2><?= Yii::t('app', 'RegisterLabel') ?></h2>

            <p><?= Yii::t('app', 'RegisterNotice01') ?></p>
            <?php $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset id="information">
                <legend><?= Yii::t('app', 'PersonalInfoLabel') ?></legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-email"><?= Yii::t('app','Full Name') ?></label>
                    <div class="col-sm-8">
                        <?= $form->field($modelGuest, 'full_name', [
                            'showLabels' => false,
                        ])->textInput(['placeholder' => Yii::t('app','Full Name')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-telephone"><?= Yii::t('app','Email') ?></label>
                    <div class="col-sm-8">
                        <?= $form->field($modelGuest, 'email', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => Yii::t('app','Email')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-fax"><?= Yii::t('app','Phone Number') ?></label>
                    <div class="col-sm-8">
                        <?= $form->field($modelGuest, 'phone_number', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => Yii::t('app','Phone Number')]); ?>
                    </div>
                </div>
            </fieldset>
            <fieldset id="password" class="register">
                <legend><?= Yii::t('app', 'AccountLabel') ?></legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-lastname"><?= Yii::t('app','Customer Username') ?></label>
                    <div class="col-sm-8">
                        <?= $form->field($modelCustomer, 'username', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => Yii::t('app','Customer Username')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname"><?= Yii::t('app','Customer Password') ?></label>
                    <div class="col-sm-8">
                        <?= $form->field($modelCustomer, 'password', [
                            'showLabels' => false
                        ])->passwordInput(['placeholder'=> Yii::t('app','Customer Password')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname"><?= Yii::t('app','Customer Re_Confirm Password') ?></label>
                    <div class="col-sm-8">
                        <?= $form->field($modelCustomer, 're_password', [
                            'showLabels' => false
                        ])->passwordInput(['placeholder'=> Yii::t('app','Customer Re_Confirm Password')]); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons">
                <div class="pull-right"><?= Yii::t('app', 'RegisterNotice02') ?><a href="#"
                                                                                   class="agree"><b><?= Yii::t('app', 'FreshGardenLabel') ?></b></a>
                    &nbsp;
                    <input type="submit" value="<?= Yii::t('app', 'RegisterLabel') ?>" class="btn btn-primary"/>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
