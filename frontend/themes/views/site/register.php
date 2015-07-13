<?php

use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'RegisterLabel');

?>
<?php require('_header.php');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <?php require('_leftMenu.php'); ?>
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
                    <div class="col-sm-12">
                        <?= $form->field($modelGuest, 'full_name', [
                            'showLabels' => true
                        ])->textInput(['placeholder' => $modelGuest->getAttributeLabel('full_name')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelGuest, 'email', [
                            'showLabels' => true
                        ])->textInput(['placeholder' => $modelGuest->getAttributeLabel('email')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelGuest, 'phone_number', [
                            'showLabels' => true
                        ])->textInput(['placeholder' => $modelGuest->getAttributeLabel('phone_number')]); ?>
                    </div>
                </div>
            </fieldset>
            <fieldset id="password" class="register">
                <legend><?= Yii::t('app', 'AccountLabel') ?></legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelCustomer, 'username', [
                            'showLabels' => true
                        ])->textInput(['placeholder' => $modelCustomer->getAttributeLabel('username')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelCustomer, 'password', [
                            'showLabels' => true
                        ])->passwordInput(['placeholder' => $modelCustomer->getAttributeLabel('password')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <div class="col-sm-12">
                        <?= $form->field($modelCustomer, 're_password', [
                            'showLabels' => true
                        ])->passwordInput(['placeholder' => $modelCustomer->getAttributeLabel('re_password')]); ?>
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
