<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
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
    echo lavrentiev\yii2toastr\Toastr::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'success',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'message' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
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
<!--                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">-->
<!--                    </a>-->
<!--                    <a href="javascript:;" class="reload" data-original-title="" title="">-->
<!--                    </a>-->
                    <a href="javascript:;" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class' => 'horizontal-form']]); ?>
                    <div class="form-body">
                        <h3 class="form-section"><?= Yii::t('app', 'Employee Details') ?></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'full_name')->textInput(['maxlength' => 100, 'placeholder' => Yii::t('app', 'Enter employee full name')]) ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter employee password')]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'dob')->widget(\kartik\date\DatePicker::classname(), [
                                    'options' => ['placeholder' => Yii::t('app', 'Enter employee birth day')],
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
                                <?= $form->field($model, 'image')->widget(\kartik\widgets\FileInput::className(), [
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
                                <?= $form->field($model, 'gender')->dropDownList([ 'Male' => 'Nam', 'Female' => 'Nữ', 'Other' => 'Khác', ], ['prompt' => Yii::t('app','Select employee gender')]) ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone_number')->textInput(['maxlength' => 15, 'placeholder' => Yii::t('app', 'Enter employee phone number')]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter employee email')]) ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'start_date')->widget(\kartik\date\DatePicker::classname(), [
                                    'options' => ['placeholder' => Yii::t('app', 'Enter employee start date')],
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
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <h3 class="form-section"><?= Yii::t('app', 'EmployeeAddress')?></h3>
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($address, 'detail')->textInput(['maxlength' => 100, 'placeholder' => Yii::t('app', 'Enter employee address')])->label(Yii::t('app', 'Address')) ?>
                            </div>
                            <div class="col-md-6">
                                <?php

                                if ($city->id) {
                                    echo $form->field($city, 'id')->dropDownList(
                                        \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                        [
                                            'prompt'=>Yii::t('app', 'Select City'),
                                            'onchange'=>'
                                        $.post( "index.php?r=employee/getdistrict&id="+$(this).val(), function( file ) {
                                            $( "select#district-id" ).length = 0;
                                            $( "select#district-id" ).html( file );
                                        });'
                                        ])->label(Yii::t('app','CityLabel'));
                                } else {
                                    echo $form->field($city, 'id')->dropDownList(
                                        \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                        ['prompt'=>Yii::t('app', 'Select City')])->label(Yii::t('app','CityLabel'));
                                }

                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
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
                                    )->label(Yii::t('app','DistrictLabel'));
                                } else {
                                    echo $form->field($address, 'district_id')->widget(\kartik\widgets\DepDrop::classname(), [
                                        'options'=>['prompt' => Yii::t('app', 'Select District')],
                                        'pluginOptions'=>[
                                            'depends'=>[Html::getInputId($city, 'id')],
                                            'placeholder'=>Yii::t('app', 'Select District'),
                                            'url'=>\yii\helpers\Url::to(['/employee/getdistrict'])
                                        ]
                                    ])->label(Yii::t('app','DistrictLabel'));;
                                }

                                ?>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">

                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <h3 class="form-section"><?= Yii::t('app', 'EmployeePermission')?></h3>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'assignments')->widget(\fsm\dualistbox\DualListbox::className(), [
                                    'items' => $item,
                                    'options' => [
                                        'moveOnSelect' => false,
                                        'selectorMinimalHeight' => '150',
                                        'nonSelectedListLabel' => '<b><i>' . Yii::t('app', 'Available') . '</i></b>',
                                        'selectedListLabel' => '<b><i>' . Yii::t('app', 'Assigned') . '</i></b>',
                                    ]
                                ])->label(false)?>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </div>
                    <div class="form-actions right">
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

