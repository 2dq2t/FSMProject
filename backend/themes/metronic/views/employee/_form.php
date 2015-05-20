<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $model backend\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

$staff_image = '';

if($model->image) {
    if(file_exists('uploads/employees/image/'. $model->id . '/' . $model->image)) {
        $staff_image = Html::img('uploads/employees/image/' . $model->id . '/' . $model->image, ['class' => 'file-preview-image']);
    }
}
?>

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

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">

    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['employee/index'], ['class' => 'btn btn-default btn-circle']) ?>

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
                    <i class="fa fa-gift"></i><?= $this->title ?>
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
<!--                <form action="#" class="form-horizontal">-->
                <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class' => 'form-horizontal']]); ?>
                    <div class="form-body">
                        <h3 class="form-section"><?= $this->title ?></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'full_name', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textInput(['maxlength' => 100, 'placeholder' => Yii::t('app', 'Enter full name')]) ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'password', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->passwordInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter password')]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'dob', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->widget(\kartik\date\DatePicker::classname(), [
                                    'options' => ['placeholder' => Yii::t('app', 'Enter birth day..')],
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
                            <div class="col-md-6">
                                <?= $form->field($model, 'image', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->widget(\kartik\widgets\FileInput::className(), [
                                    'options' => [
                                        'accept' => 'image/*'
                                    ],
                                    'pluginOptions' => [
                                        'showCaption' => true,
                                        'allowedFileExtensions' => ['jpeg', 'jpg', 'gif', 'png'],
                                        'showUpload' => false,
                                        'initialPreview' => $staff_image,
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
                                <?= $form->field($model, 'phone_number', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textInput(['maxlength' => 15, 'placeholder' => Yii::t('app', 'Enter phone number')]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'email', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter email')]) ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'note', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->textarea(['rows' => 6, 'placeholder' => Yii::t('app', 'Enter note')]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'start_date', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->widget(\kartik\date\DatePicker::classname(), [
                                    'options' => ['placeholder' => Yii::t('app', 'Enter start date work..')],
                                    'removeButton' => false,
                                    'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'todayHighlight' => true
                                    ]
                                ]); ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'status', [
                                    'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                ])->checkbox() ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <h3 class="form-section"><?= Yii::t('app', 'Address Info')?></h3>
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
                                                'prompt'=>Yii::t('app', 'Select City'),
                                                'onchange'=>'
                                        $.post( "index.php?r=employee/getdistrict&id="+$(this).val(), function( file ) {
                                            $( "select#district-id" ).length = 0;
                                            $( "select#district-id" ).html( file );
                                        });'
                                            ]);
                                } else {
                                    echo $form->field($city, 'id', [
                                        'template' => "<label class='control-label col-md-3'>{label}</label><div class='col-md-9'>{input}{error}</div>"
                                    ])->dropDownList(
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
                                            'url'=>\yii\helpers\Url::to(['/employee/getdistrict'])
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
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <?= $this->blocks['submit']?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<!--                </form>-->
                <?php ActiveForm::end(); ?>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

