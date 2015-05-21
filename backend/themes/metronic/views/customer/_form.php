<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Guest */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

$user_avatar = '';

if($model->avatar) {
    if(file_exists('../../frontend/web/uploads/users/avatar/'. $model->id . '/' . $model->avatar)) {
        $user_avatar = Html::img('../../frontend/web/uploads/users/avatar/' . $model->id . '/' . $model->avatar, ['class' => 'file-preview-image']);
    }
}

?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo \kartik\alert\Alert::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : \kartik\alert\Alert::TYPE_DANGER,
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
        'delay' => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
        'showSeparator' => true,
        'options' => ['format' => 'raw']
    ]);
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">
    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['customer/index'], ['class' => 'btn btn-default btn-circle']) ?>

    <?php if ($model->isNewRecord): ?>
        <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>
    <?php endif; ?>

    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') : '<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

</div>
<?php $this->endBlock('submit'); ?>

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
<div class="row">
    <div class="col-md-6 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> User Info
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
                    </a>
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter username')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'placeholder'=> Yii::t('app', 'Enter password')]) ?>
                    </div>
                    <div class="form-group">
                        <?php //$form->field($model, 're_password')->passwordInput(['maxlength' => 255, 'placeholder'=> 'Enter password']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'avatar')->widget(\kartik\widgets\FileInput::className(), [
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
                                    'footer' => '<div class="file-thumbnail-footer">'.
                                        '<div class="file-caption-name">{caption}</div>'.
                                        '<div class="file-actions">'.
                                        '<div class="file-footer-buttons">'.
                                        '<button type="button" class="kv-file-remove btn btn-xs btn-default" title="Remove file">'.
                                        '<i class="glyphicon glyphicon-trash text-danger"></i>'.
                                        '</button>'.
                                        '</div>'.
                                        '</div>'.
                                        '</div>'
                                ]
                            ]
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'dob')->widget(\kartik\date\DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('app', 'Enter birth date ...')],
                            'removeButton' => false,
                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'endDate' => '+0d',
                                'todayHighlight' => true
                            ]
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'gender')->dropDownList([ 'Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other', ], ['prompt' => '']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'status')
                            ->checkbox() ?>
                    </div>
                </div>
                <div class="form-actions right">
                    <?php echo $this->blocks['submit']; ?>
                </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
    <div class="col-md-6 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-users"></i> Customer Info
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
                    </a>
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <?= $form->field($guest, 'full_name')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Eneter customer fullname')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($guest, 'email')->input('email', ['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter customer email')]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($guest, 'phone_number')->textInput(['maxlength' => 15 , 'placeholder' => Yii::t('app', 'Enter customer phone number')]) ?>
                    </div>
                </div>
                <div class="form-actions right">
                    <?php echo $this->blocks['submit']; ?>
                </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-map-marker"></i> Address Info
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
                    </a>
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <?= $form->field($address, 'detail')->textInput(['maxlength' => 100]) ?>
                    </div>
                    <div class="form-group">
                        <?php

                        if ($city->id) {
                            echo $form->field($city, 'id')
                                ->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                    [
                                        'prompt'=> Yii::t('app', 'Select City'),
                                        'onchange'=>'
                                            $.post( "index.php?r=customer/getdistrict&id="+$(this).val(), function( file ) {
                                                $( "select#district-id" ).length = 0;
                                                $( "select#district-id" ).html( file );
                                                var event = new Event("change");
                                                document.getElementById("district-id").dispatchEvent(event);
                                            });'
                                    ]);
                        } else {
                            echo $form->field($city, 'id')
                                ->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                    ['prompt'=>Yii::t('app', 'Select City')]);
                        }

                        ?>
                    </div>
                    <div class="form-group">

                        <?php

                        if ($city->id) {
                            echo $form->field($address, 'district_id')->dropDownList(
                                \yii\helpers\ArrayHelper::map(
                                    \common\models\District::find()
                                        ->where(['city_id' => $city->id])
                                        ->all(), 'id', 'name'),
                                [
                                    'prompt'=>Yii::t('app', 'Select District'),
                                ]
                            );
                        } else {
                            echo $form->field($address, 'district_id')->widget(\kartik\widgets\DepDrop::classname(), [
                                'options'=>['prompt' => Yii::t('app', 'Select District')],
                                'pluginOptions'=>[
                                    'depends'=>[Html::getInputId($city, 'id')],
                                    'placeholder'=>Yii::t('app', 'Select District'),
                                    'url'=>\yii\helpers\Url::to(['/customer/getdistrict'])
                                ]
                            ]);
                        }

                        ?>
                    </div>
                    <div class="form-group">

                    </div>
                </div>
                <div class="form-actions right">
                    <?php echo $this->blocks['submit']; ?>
                </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>
<?php ActiveForm::end(); ?>
