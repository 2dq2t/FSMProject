<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\alert\Alert;

$this->title = Yii::t('app', 'ChangeAccInfoLabel');
$baseUrl = Yii::$app->request->baseUrl;
require('../themes/views/site/_header.php');
?>

<?php
$user_avatar = '';
if (isset($modelCustomer->avatar)) {
    $user_avatar = Html::img('../../frontend/web/uploads/users/avatar/' . $modelCustomer->id . '/' . $modelCustomer->avatar, ['class' => 'file-preview-image']);
}
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
            <li><a href="index.php"><i
                        class="fa fa-home"></i></a></li>
            <li>
                <a href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id; ?>"><?= Yii::t('app', 'AccountLabel') ?></a>
            </li>
            <li>
                <a href="index.php?r=customer/update&id=<?= Yii::$app->user->identity->id; ?>"><?= Yii::t('app', 'ChangeAccInfoLabel') ?></a>
            </li>
        </ul>
        <div id="content" class="col-sm-9">
            <?php $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
                'options' => ['enctype' => 'multipart/form-data']
            ]);
            ?>
            <fieldset>
                <legend><?= Yii::t('app', 'AccountInfoLabel') ?></legend>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-firstname"><?= Yii::t('app','Customer Avatar') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'avatar')->widget(\kartik\widgets\FileInput::className(), [
                            'options' => [
                                'accept' => 'image/*'
                            ],
                            'pluginOptions' => [
                                'showCaption' => true,
                                'allowedFileExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
                                'showUpload' => false,
                                'initialPreview' => $user_avatar,
                                'overwriteInitial' => true,
                                'layoutTemplates' => [
                                    'footer' => '<div class="file-thumbnail-footer">' .
                                        '<div class="file-caption-name">{caption}</div>' .
                                        '<div class="file-actions">' .
                                        '<div class="file-footer-buttons">' .
                                        '<button type="button" class="kv-file-remove btn btn-xs btn-default" title="Remove file">' .
                                        '<i class="glyphicon glyphicon-trash text-danger"></i>' .
                                        '</button>' .
                                        '</div>' .
                                        '</div>' .
                                        '</div>'
                                ]
                            ]
                        ])->label(false) ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-lastname"><?= Yii::t('app','Customer Username') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'username', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => Yii::t('app','Customer Username')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-email"><?= Yii::t('app','Full Name') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($modelGuest, 'full_name', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => Yii::t('app','Full Name')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-telephone"><?= Yii::t('app','Email') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($modelGuest, 'email', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => Yii::t('app','Email')]); ?>
                    </div>
                </div>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-fax"><?= Yii::t('app','Phone Number') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($modelGuest, 'phone_number', [
                            'showLabels' => false
                        ])->textInput(['placeholder' => Yii::t('app','Phone Number')]); ?>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-fax"><?= Yii::t('app','Customer Gender') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'gender', [
                            'showLabels' => false
                        ])->dropDownList(['Male' => 'Nam', 'Female' => 'Nữ', 'Other' => 'Khác']); ?>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label"
                           for="input-fax"><?= Yii::t('app','Customer Date of birth') ?></label>

                    <div class="col-sm-10">
                        <?= $form->field($modelCustomer, 'dob', [
                            'showLabels' => false
                        ])->widget(DatePicker::className(), [
                            'removeButton' => false,
                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'endDate' => '+0d',
                                'todayHighlight' => true,
                                'format' => 'mm/dd/yyyy'
                            ],
                        ]); ?>
                    </div>
                </div>
            </fieldset>
            <div class="buttons clearfix">
                <div class="pull-left"><a
                        href="index.php?r=customer/manageacc&id=<?= Yii::$app->user->identity->id; ?>"
                        class="btn btn-default"><?= Yii::t('app', 'BackLabel') ?></a></div>
                <div class="pull-right">
                    <input type="submit" value="<?= Yii::t('app', 'SaveLabel') ?>" class="btn btn-primary"/>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>