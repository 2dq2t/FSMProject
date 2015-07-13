<?php

use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'LoginLabel');

?>
<?php require('_header.php');
?>
<div class="container content-inner">

    <div class="row content-subinner">
        <?php require('_leftMenu.php'); ?>
        <div id="content" class="col-sm-9">
            <ul class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i></a></li>
                <li><a href="#"><?= Yii::t('app', 'AccountLabel') ?></a></li>
                <li><a href="#"><?= Yii::t('app', 'RegisterLabel') ?></a></li>
            </ul>

            <div class="row">
                <div class="col-sm-6 register">
                    <div class="well">
                        <h2><?= Yii::t('app', 'NewCustomerLabel') ?></h2>

                        <p><strong><?= Yii::t('app', 'RegisterLabel') ?></strong></p>

                        <p><?= Yii::t('app', 'LoginNotice01') ?></p>
                        <a href="index.php?r=site/register"
                           class="btn btn-primary"><?= Yii::t('app', 'ContinueLabel') ?></a>
                    </div>
                </div>
                <div class="col-sm-6 login">
                    <div class="well">
                        <h2><?= Yii::t('app', 'CustomerLabel') ?></h2>

                        <p><strong><?= Yii::t('app', 'LoginNotice02') ?></strong></p>
                        <?php $form = ActiveForm::begin(['id' => 'form-login', 'method' => 'post']); ?>
                        <div class="form-group">
                            <label class="control-label"
                                   for="input-email"><?= $model->generateAttributeLabel('username') ?></label>
                            <?= $form->field($model, 'username')
                                ->label(false)->textInput(['placeholder' => $model->generateAttributeLabel('username')]); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label"
                                   for="input-password"><?= $model->generateAttributeLabel('password') ?></label>
                            <?= $form->field($model, 'password')
                                ->label(false)->passwordInput(['placeholder' => $model->generateAttributeLabel('password')]); ?>
                            <div class="forget-password"><a
                                    href="index.php?r=site/request-password-reset"><?= Yii::t('app', 'ForgottenPasswordLabel') ?>
                                    ?</a></div>
                        </div>
                        <input type="submit" value="<?= Yii::t('app', 'LoginLabel') ?>" class="btn btn-primary"/>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
