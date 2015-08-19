<?php
/**
 * Created by PhpStorm.
 * User: TuDA
 * Date: 8/17/2015
 * Time: 11:46 PM
 */
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'ChangeAccInfoLabel');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$staff_image = '';

if ($model->image) {
    if (file_exists('uploads/employees/image/' . $model->id . '/' . $model->image)) {
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
    <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn green-haze', 'name' => 'action', 'value' => 'save']) ?>
    <?= Html::a(Yii::t('app', 'Back'), ['site/index'], ['class' => 'btn default']) ?>
</div>
<?php $this->endBlock('submit'); ?>

<!-- BEGIN PROFILE CONTENT -->
<div class="profile-content">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase"><?=Yii::t('app','Employee Details')?></span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?=Yii::t('app','Employee Details')?></a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"><?=Yii::t('app','ChangePassInfoLabel')?></a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body">
                    <div class="tab-content">
                        <!-- PERSONAL INFO TAB -->
                        <div class="tab-pane active" id="tab_1_1">
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'horizontal-form']]); ?>
                            <div class="form-body">

                                <div class="row">
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
                                        ]) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'full_name')->textInput(['maxlength' => 100, 'placeholder' => Yii::t('app', 'Enter employee full name')]) ?>
                                        <?= $form->field($model, 'dob')->widget(\kartik\date\DatePicker::classname(), [
                                            'options' => ['placeholder' => Yii::t('app', 'Enter employee birth day')],
                                            'removeButton' => false,
                                            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'endDate' => '+0d',
                                                'todayHighlight' => true
                                            ]
                                        ]); ?>
                                        <?= $form->field($model, 'gender')->dropDownList(['Male' => 'Nam', 'Female' => 'Nữ', 'Other' => 'Khác',]) ?>
                                        <?= $form->field($model, 'phone_number')->textInput(['maxlength' => 15, 'placeholder' => Yii::t('app', 'Enter employee phone number')]) ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('app', 'Enter employee email'), 'disabled' => true,]) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($address, 'detail')->textInput(['maxlength' => 100, 'placeholder' => Yii::t('app', 'Enter employee address')])->label(Yii::t('app', 'Address')) ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <?php

                                        if ($city->id) {
                                            echo $form->field($city, 'id')->dropDownList(
                                                \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                                [
                                                    'prompt' => Yii::t('app', 'Select City'),
                                                    'onchange' => '
                                        $.post( "index.php?r=employee/getdistrict&id="+$(this).val(), function( file ) {
                                            $( "select#address-district_id" ).length = 0;
                                            $( "select#address-district_id" ).html( file );
                                        });'
                                                ])->label(Yii::t('app', 'CityLabel'));
                                        } else {
                                            echo $form->field($city, 'id')->dropDownList(
                                                \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'name'),
                                                ['prompt' => Yii::t('app', 'Select City')])->label(Yii::t('app', 'CityLabel'));
                                        }

                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php

                                        if ($city->id) {
                                            echo $form->field($address, 'district_id')->dropDownList(
                                                \yii\helpers\ArrayHelper::map(
                                                    \common\models\District::find()
                                                        ->where(['city_id' => $city->id])
                                                        ->all(), 'id', 'name'),
                                                [
                                                    'prompt' => Yii::t('app', 'Select District'),
                                                ]
                                            )->label(Yii::t('app', 'DistrictLabel'));
                                        } else {
                                            echo $form->field($address, 'district_id')->widget(\kartik\widgets\DepDrop::classname(), [
                                                'options' => ['prompt' => Yii::t('app', 'Select District')],
                                                'pluginOptions' => [
                                                    'depends' => [Html::getInputId($city, 'id')],
                                                    'placeholder' => Yii::t('app', 'Select District'),
                                                    'url' => \yii\helpers\Url::to(['/employee/getdistrict'])
                                                ]
                                            ])->label(Yii::t('app', 'DistrictLabel'));;
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="margin-top-10">
                                <?= $this->blocks['submit'] ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <!-- END FORM-->
                        </div>
                        <!-- END PERSONAL INFO TAB -->
                        <!-- CHANGE PASSWORD TAB -->
                        <script>
                            function myFunction()
                            {
                                $.validator.addMethod("pwcheckspechars", function (value) {
                                    return /^[A-Za-z0-9_]+$/.test(value)
                                });
                                var validator = $("#change_password_form").validate({
                                    rules: {
                                        password :{
                                            required: true,
                                            pwcheckspechars: true
                                        },
                                        confirmpassword:{
                                            equalTo: "#password"
                                        }
                                    },
                                    messages: {
                                        password :{
                                            required: "Mật Khẩu không được để trống.",
                                            pwcheckspechars: "Mật Khẩu không được chứa ký tự đặc biệt."
                                        },
                                        confirmpassword :"Nhập Lại Mật Khẩu phải lặp lại chính xác."
                                    }
                                });
                                if(validator.form()){
                                    $.ajax({
                                        url: '<?php echo Yii::$app->request->baseUrl. '/index.php?r=account/change&id='.Yii::$app->user->id ?>',
                                        type: 'post',
                                        data: {new_password: $("#password").val()},
                                        success: function (data) {
                                            location.reload();
                                        }
                                    });
                                }
                            }
                        </script>
                        <div class="tab-pane" id="tab_1_3">
                            <form action="#" id="change_password_form" name="change_password_form">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app','Employee Password')?></label>
                                    <input id="password" name="password" type="password" class="form-control"/>
                                </div>
                                <div id="foo" class="form-group">
                                    <label class="control-label"><?=Yii::t('app','Employee Re_Password')?></label>
                                    <input id="confirmpassword" name="confirmpassword" type="password" class="form-control"/>
                                </div>

                                <div class="margin-top-10">
                                    <a href="#" class="btn green-haze" onclick="myFunction();">
                                        <?=Yii::t('app','Update')?></a>
                                    <?= Html::a(Yii::t('app', 'Back'), ['site/index'], ['class' => 'btn default']) ?>
                                </div>
                            </form>
                        </div>
                        <!-- END CHANGE PASSWORD TAB -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PROFILE CONTENT -->

