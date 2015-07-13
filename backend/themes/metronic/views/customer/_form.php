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
    echo lavrentiev\yii2toastr\Toastr::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'success',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
        'message' => (!empty($message['message'])) ? $message['message'] : Yii::t('app', 'Message Not Set!'),
        'clear' => false,
        'options' => [
            "closeButton" => true,
            "positionClass" => "toast-top-right",
            "timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
        ]
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

<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Form Sample
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="reload" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class' => 'form-horizontal']]); ?>
                    <div class="form-body">
                        <h3 class="form-section"><?= Yii::t('app', 'Person Info')?></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'username', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter username')]) ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'password', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->passwordInput(['maxlength' => 255, 'placeholder'=> Yii::t('app', 'Enter password')]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'avatar', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->widget(\kartik\widgets\FileInput::className(), [
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
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'dob', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->widget(\kartik\date\DatePicker::classname(), [
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
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'gender', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->dropDownList([ 'Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other', ], ['prompt' => '']) ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($guest, 'full_name', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Eneter customer fullname')]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($guest, 'email', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->input('email', ['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter customer email')]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($guest, 'phone_number', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textInput(['maxlength' => 15 , 'placeholder' => Yii::t('app', 'Enter customer phone number')]) ?>
                            </div>
                        </div>
                        <h3 class="form-section"><?= Yii::t('app', 'Address')?></h3>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($address, 'detail', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textInput(['maxlength' => 100]) ?>

                            </div>
                            <div class="col-md-6">
                                <?php

                                if ($city->id) {
                                    echo $form->field($city, 'id', [
                                        'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                    ])->dropDownList(
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
                                    echo $form->field($city, 'id', [
                                        'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                    ])
                                        ->dropDownList(
                                            \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                            ['prompt'=>Yii::t('app', 'Select City')]);
                                }

                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php

                                if ($city->id) {
                                    echo $form->field($address, 'district_id', [
                                        'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                    ])->dropDownList(
                                        \yii\helpers\ArrayHelper::map(
                                            \common\models\District::find()
                                                ->where(['city_id' => $city->id])
                                                ->all(), 'id', 'name'),
                                        [
                                            'prompt'=>Yii::t('app', 'Select District'),
                                        ]
                                    );
                                } else {
                                    echo $form->field($address, 'district_id', [
                                        'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                    ])->widget(\kartik\widgets\DepDrop::classname(), [
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
                            <!--/span-->
                            <div class="col-md-6">
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <?= $this->blocks['submit']?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
