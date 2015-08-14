<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\alert\Alert;
$this->title = Yii::t('app', 'ChangeAddressInfoLabel');
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
            <li><a href="index.php?r=account/changeaddress&id=<?= Yii::$app->user->identity->id;?>"><?= Yii::t('app', 'ChangeAddressInfoLabel') ?></a></li>
        </ul>
        <div id="content" class="col-sm-9">
            <?php $form = ActiveForm::begin([
                'type'=>ActiveForm::TYPE_HORIZONTAL,
                'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
            ]);
            ?>
            <fieldset>
                <legend><?=Yii::t('app','AddressInfoLabel')?></legend>
                <div class="form-group required" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname"><?= Yii::t('app','CustomerAddress') ?></label>
                    <div class="col-sm-10">
                        <?php
                            if(isset($modelUpdateAddress)){
                                echo $form->field($modelUpdateAddress, 'detail',[
                                    'showLabels'=>false
                                ])->textInput(['placeholder' => Yii::t('app','CustomerAddress')]);
                            }else{
                                echo $form->field($modelAddress, 'detail',[
                                    'showLabels'=>false
                                ])->textInput(['placeholder' => Yii::t('app','CustomerAddress')]);
                            }
                        ?>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname"><?=Yii::t('app','CityLabel')?></label>
                    <div class="col-sm-10">
                        <?php
                            if (isset($modelUpdateAddress)) {
                                echo $form->field($modelUpdateCity, 'id')->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                    [
                                        'prompt'=> Yii::t('app', '- Chọn Tỉnh / Thành phố -'),
                                        'onchange'=>'
                                                $.post( "index.php?r=account/getdistrict&id="+$(this).val(), function( file ) {
                                                    $( "select#district-id" ).length = 0;
                                                    $( "select#district-id" ).html( file );
                                                    var event = new Event("change");
                                                    document.getElementById("district-id").dispatchEvent(event);
                                                });'
                                    ])->label(false);
                            } else {
                                echo $form->field($modelCity, 'id')->dropDownList(
                                        \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                        ['prompt'=>Yii::t('app', '- Chọn Tỉnh / Thành phố -')])->label(false);
                            }
                        ?>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label class="col-sm-2 control-label" for="input-lastname"><?=Yii::t('app','DistrictLabel')?></label>
                    <div class="col-sm-10">
                        <?php
                            if (isset($modelUpdateAddress)) {
                                echo $form->field($modelUpdateAddress, 'district_id')->dropDownList(
                                    \yii\helpers\ArrayHelper::map(
                                        \common\models\District::find()
                                            ->where(['city_id' => $modelUpdateCity->id])
                                            ->all(), 'id', 'name'),
                                    [
                                        'prompt'=>Yii::t('app', '- Chọn Quận / Huyện -'),
                                    ]
                                )->label(false);
                            } else {
                                echo $form->field($modelAddress, 'district_id')->widget(\kartik\widgets\DepDrop::classname(), [
                                    'options'=>['prompt' => Yii::t('app', '- Chọn Quận / Huyện -')],
                                    'pluginOptions'=>[
                                        'depends'=>[Html::getInputId($modelCity, 'id')],
                                        'placeholder'=>Yii::t('app', '- Chọn Quận / Huyện -'),
                                        'url'=>\yii\helpers\Url::to(['/account/getdistrict'])
                                    ]
                                ])->label(false);
                            }
                        ?>
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