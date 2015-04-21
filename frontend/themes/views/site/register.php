<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\City;
use frontend\models\District;
use frontend\models\Ward;
use kartik\widgets\DepDrop;
/**
 * Created by PhpStorm.
 * User: TuDA
 * Date: 4/14/2015
 * Time: 12:47 AM
 */
?>

<script type="text/javascript">
    var numDays = {
        '1': 31, '2': 28, '3': 31, '4': 30, '5': 31, '6': 30,
        '7': 31, '8': 31, '9': 30, '10': 31, '11': 30, '12': 31
    };

    function setDays(oMonthSel, oDaysSel, oYearSel)
    {
        var nDays, oDaysSelLgth, opt, i = 1;
        nDays = numDays[oMonthSel[oMonthSel.selectedIndex].value];
        if (nDays == 28 && oYearSel[oYearSel.selectedIndex].value % 4 == 0)
            ++nDays;
        oDaysSelLgth = oDaysSel.length;
        if (nDays != oDaysSelLgth)
        {
            if (nDays < oDaysSelLgth)
                oDaysSel.length = nDays;
            else for (i; i < nDays - oDaysSelLgth + 1; i++)
            {
                opt = new Option(oDaysSelLgth + i, oDaysSelLgth + i);
                oDaysSel.options[oDaysSel.length] = opt;
            }
        }
        var oForm = oMonthSel.form;
        var month = oMonthSel.options[oMonthSel.selectedIndex].value;
        var day = oDaysSel.options[oDaysSel.selectedIndex].value;
        var year = oYearSel.options[oYearSel.selectedIndex].value;
        document.getElementById("useraccount-dob").value = year + '-' + month + '-' + day;
    }
</script>

<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'form-register', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>
            <div class="col-md-6">
                <section class="section register inner-right-xs">
                    <h2 class="bordered">Tạo tài khoản mới</h2>
                    <p>Hãy đăng kí để trở thành khách hàng của FSM</p>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Thông tin cá nhân</legend>
                            <div class="field-row">
                                <label>Giới tính</label>
                                <?=
                                $form->field($modelUserAccount, 'Gender')
                                    ->radioList(
                                        [ 'Male' => 'Nam', 'Female' => 'Nữ', 'Other' => 'Khác', ],
                                        [
                                            'item' => function($index, $label, $name, $checked, $value) {
                                                $return = '<input type="radio" class="le-radio" style="width: 10%;" name="' . $name . '" value="' . $value . '">';
                                                $return .= '<i></i>';
                                                $return .= '<span style="margin-right: 25px;">' . ucwords($label) . '</span>';
                                                return $return;
                                            }
                                        ]
                                    )
                                    ->label(false);
                                ?>
                            </div>

                            <div class="field-row">
                                <label>Họ và tên</label>
                                <?= $form->field($modelUser, 'FullName', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Ngày sinh</label>
                                <select class="le-select" name="days" id="days" onchange="setDays(months,this,years)">
                                    <option value="0">Ngày</option>
                                    <?php
                                    for ($day = 1; $day <= 31; $day++) {
                                        echo "<option value=\"$day\">$day</option>\n";
                                    }
                                    ?>
                                </select>

                                <select class="le-select" name="months" id="months" onchange="setDays(this,days,years)">
                                    <option value="0">Tháng</option>
                                    <?php
                                    $months = array (1 => 'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',   'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12');
                                    foreach ($months as $key => $value) {
                                        echo "<option value=\"$key\">$value</option>\n";
                                    }
                                    ?>
                                </select>

                                <select class="le-select" name="years" id="years" onchange="setDays(months,days,this)">
                                    <option value="0">Năm</option>
                                    <?php
                                    for ($year = 1930; $year <= 2015; $year++) {
                                        echo "<option value=\"$year\">$year</option>\n";
                                    }
                                    ?>
                                </select>


                                <?= $form->field($modelUserAccount, 'DOB', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->hiddenInput()->label(false); ?>

                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Email</label>
                                <?= $form->field($modelUser, 'Email', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Mật khẩu của bạn</legend>
                            <div class="field-row">
                                <label>Tên đăng nhập</label>
                                <?= $form->field($modelUserAccount, 'UserName', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->


                            <div class="field-row">
                                <label>Mật khẩu</label>
                                <?= $form->field($modelUserAccount, 'Password', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->passwordInput()->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Xác Nhận Mật khẩu</label>
                                <?= $form->field($modelUserAccount, 'RePassword', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->passwordInput()->label(false); ?>
                            </div><!-- /.field-row -->
                        </fieldset>

                    <!-- /.cf-style-1 -->

                </section><!-- /.sign-in -->
            </div><!-- /.col -->

            <div class="col-md-6">
                <section class="section register inner-left-xs">
                    <h2 class="bordered">&nbsp</h2>
                    <p>&nbsp</p>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Thông tin liên hệ</legend>

                            <div class="field-row">
                                <label>Số điện thoại</label>
                                <?= $form->field($modelUser, 'PhoneNumber', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Số nhà / đường phố, thôn xóm</label>
                                <?= $form->field($modelAddress, 'Detail', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Tỉnh / Thành Phố</label>
                                <?= $form->field($modelCity, 'Name')->dropDownList(City::getCity(),
                                    ['id' => 'city-id', 'class'=>'le-select-address', 'prompt' => '- Chọn Tỉnh / Thành phố -'])
                                    ->label(false);
                                ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Quận / Huyện</label>
                                <?= $form->field($modelDistrict, 'Name')->widget(DepDrop::classname(), [
                                    'options'=>['id'=>'district-id', 'class'=>'le-select-address','prompt' => '- Chọn Quận / Huyện -'],
                                    'pluginOptions'=>[
                                        'depends'=>['city-id'],
                                        'placeholder'=>'- Chọn Quận / Huyện -',
                                        'url'=>\yii\helpers\Url::to(['/site/subcat'])
                                    ]
                                ])->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Xã / Phường</label>
                                <?= $form->field($modelAddress, 'Ward_Id')->widget(DepDrop::classname(), [
                                    'options'=>['id'=>'ward-id', 'class'=>'le-select-address','prompt' => '- Chọn Xã / Phường -'],
                                    'pluginOptions'=>[
                                        'depends'=>['city-id','district-id'],
                                        'placeholder'=>'- Chọn Xã / Phường -',
                                        'url'=>\yii\helpers\Url::to(['/site/prod'])
                                    ]
                                ])->label(false); ?>
                            </div><!-- /.field-row -->
                        </fieldset>
                   <!-- /.cf-style-1 -->
                    <div class="buttons-holder">
                        <button type="submit" class="le-button huge" name="submit">Đăng ký</button>
                    </div><!-- /.buttons-holder -->
                </section><!-- /.sign-in -->

            </div><!-- /.col -->
        <?php ActiveForm::end(); ?>
    </div><!-- /.row -->
</div><!-- /.container -->