<?php

use yii\widgets\ActiveForm;
use common\models\City;
use kartik\widgets\DepDrop;
?>
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
                                $form->field($modelCustomer, 'gender')
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
                                <?= $form->field($modelGuest, 'full_name', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                        'maxlength' => 255,
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Ngày sinh</label>
                                <select class="le-select" name="days" id="days">
                                    <option value=" " selected="selected">Ngày</option>
                                    <?php
                                    for ($day = 1; $day <= 31; $day++) {
                                        echo "<option value=\"$day\">$day</option>\n";
                                    }
                                    ?>
                                </select>

                                <select class="le-select" name="months" id="months">
                                    <option value=" " selected="selected">Tháng</option>
                                    <?php
                                    $months = array (1 => 'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',   'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12');
                                    foreach ($months as $key => $value) {
                                        echo "<option value=\"$key\">$value</option>\n";
                                    }
                                    ?>
                                </select>

                                <select class="le-select" name="years" id="years">
                                    <option value=" " selected="selected">Năm</option>
                                    <?php
                                    for ($year = 1930; $year <= 2015; $year++) {
                                        echo "<option value=\"$year\">$year</option>\n";
                                    }
                                    ?>
                                </select>

                                <?= $form->field($modelCustomer, 'dob', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                    ],
                                ])->hiddenInput()->label(false); ?>

                                <script type="text/javascript">
                                    function daysInMonth(month, year) {
                                        var days = new Date(year, month, 0);
                                        return days.getDate();
                                    }
                                    function setDayDrop(dyear, dmonth, dday) {
                                        var year = dyear.options[dyear.selectedIndex].value;
                                        var month = dmonth.options[dmonth.selectedIndex].value;
                                        var day = dday.options[dday.selectedIndex].value;
                                        if (day == ' ') {
                                            var days = (year == ' ' || month == ' ') ? 31 : daysInMonth(month, year);
                                            dday.options.length = 0;
                                            dday.options[dday.options.length] = new Option(' ', ' ');
                                            for (var i = 1; i <= days; i++)
                                                dday.options[dday.options.length] = new Option(i, i);
                                        }
                                    }
                                    function setDay() {
                                        var year = document.getElementById('years');
                                        var month = document.getElementById('months');
                                        var day = document.getElementById('days');
                                        setDayDrop(year, month, day);
                                        document.getElementById("customer-dob").value = year.value + '-' + month.value + '-' + day.value;
                                    }
                                    document.getElementById('years').onchange = setDay;
                                    document.getElementById('months').onchange = setDay;
                                    document.getElementById('days').onchange = setDay;
                                </script>

                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Email</label>
                                <?= $form->field($modelGuest, 'email', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                        'maxlength' => 255,
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Mật khẩu của bạn</legend>
                            <div class="field-row">
                                <label>Tên đăng nhập</label>
                                <?= $form->field($modelCustomer, 'username', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                        'maxlength' => 255,
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->


                            <div class="field-row">
                                <label>Mật khẩu</label>
                                <?= $form->field($modelCustomer, 'password', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                        'maxlength' => 255,
                                    ],
                                ])->passwordInput()->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Xác Nhận Mật khẩu</label>
                                <?= $form->field($modelCustomer, 're_password', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                        'maxlength' => 255,
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
                                <?= $form->field($modelGuest, 'phone_number', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                        'maxlength' => 15,
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Số nhà / đường phố, thôn xóm</label>
                                <?= $form->field($modelAddress, 'detail', [
                                    'inputOptions' => [
                                        'class' => 'le-input',
                                        'maxlength' => 100,
                                    ],
                                ])->label(false); ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Tỉnh / Thành Phố</label>
                                <?= $form->field($modelCity, 'name')->dropDownList(City::getCity(),
                                    ['id' => 'city-id', 'class'=>'le-select-address', 'prompt' => '- Chọn Tỉnh / Thành phố -'])
                                    ->label(false);
                                ?>
                            </div><!-- /.field-row -->

                            <div class="field-row">
                                <label>Quận / Huyện</label>
                                <?= $form->field($modelDistrict, 'name')->widget(DepDrop::classname(), [
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
                                <?= $form->field($modelAddress, 'ward_id')->widget(DepDrop::classname(), [
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
</div>