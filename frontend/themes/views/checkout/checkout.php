<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */

use kartik\widgets\ActiveForm;
use kartik\alert\Alert;

$baseUrl = Yii::$app->request->baseUrl;
$this->title = Yii::t('app', 'CheckoutLabel');
?>
<?php
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="<?= $baseUrl ?>"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=cart/view-cart"; ?>"
               title="Danh mục yêu thích"><?= Yii::t('app', 'ShoppingCartLabel') ?></a>
        </li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=checkout/checkout"; ?>"
               title="Danh mục yêu thích"><?= Yii::t('app', 'CheckoutLabel') ?></a>
        </li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php
            echo $this->render('/layouts/_category.php');
            echo $this->render('/layouts/_leftBanner');
            echo $this->render('/layouts/_specialProduct.php');
            echo $this->render('/layouts/_bestSeller.php');
            ?>
        </column>
        <div id="content" class="col-sm-9">
            <h1 class="page-title"><?= Yii::t('app', 'CheckoutLabel') ?></h1>

            <div class="panel-group" id="accordion">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a href="#collapse-checkout-option" data-toggle="collapse"
                                                   data-parent="#accordion"
                                                   class="accordion-toggle"><?= Yii::t('app', 'Checkout Step1') ?>
                                <i class="fa fa-caret-down"></i>
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse <?php if (!empty($continueStep1)) echo $continueStep1; ?>"
                         id="collapse-checkout-option" style="height: auto;">
                        <div class="panel-body">
                            <div class="row">
                                <?php if (Yii::$app->user->isGuest) { ?>
                                    <?php \yii\widgets\ActiveForm::begin() ?>
                                    <div class="col-sm-6">
                                        <h2><?= Yii::t('app', 'Checkout NewCustomer') ?></h2>

                                        <p><?= Yii::t('app', 'Checkout Option') ?></p>

                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="account" value="register" checked="checked">
                                                <?= Yii::t('app', 'Checkout RegisterAccount') ?></label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="account" value="guest">
                                                <?= Yii::t('app', 'Checkout GuestAccount') ?></label>
                                        </div>
                                        </br>
                                        <p><?= Yii::t('app', 'Checkout NewCustomer Description') ?></p>
                                        <input type="submit" value="<?= Yii::t('app', 'ContinueLabel') ?> "
                                               id="button-account" data-loading-text="Loading..."
                                               class="btn btn-primary">
                                    </div>
                                    <?php \yii\widgets\ActiveForm::end() ?>
                                    <div class="col-sm-6">
                                        <h2><?= Yii::t('app', 'Checkout Login') ?></h2>

                                        <p><?= Yii::t('app', 'Checkout ReturningCustomer') ?></p>
                                        <?php if (!empty($modelLogin)): ?>
                                            <?php $form = yii\bootstrap\ActiveForm::begin(['id' => 'form-login', 'method' => 'post']); ?>
                                            <div class="form-group">
                                                <label class="control-label"
                                                       for="input-email"><?= Yii::t('app', 'FLoginUserName') ?></label>
                                                <?= $form->field($modelLogin, 'username')
                                                    ->label(false)->textInput(['placeholder' => Yii::t('app', 'FLoginUserName')]); ?>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label"
                                                       for="input-password"><?= Yii::t('app', 'FLoginPassword') ?></label>
                                                <?= $form->field($modelLogin, 'password')
                                                    ->label(false)->passwordInput(['placeholder' => Yii::t('app', 'FLoginPassword')]); ?>
                                                <div class="forget-password"><a
                                                        href="../web/index.php?r=account/request-password-reset"><?= Yii::t('app', 'ForgottenPasswordLabel') ?>
                                                        ?</a></div>
                                            </div>
                                            <input type="submit" value="<?= Yii::t('app', 'LoginLabel') ?>"
                                                   class="btn btn-primary"/>
                                            <?php yii\bootstrap\ActiveForm::end(); endif ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-sm-12"> <?= Yii::t('app', 'Checkout Step1 Customer') ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a href="#collapse-payment-address" data-toggle="collapse"
                                                   data-parent="#accordion"
                                                   class="accordion-toggle"><?= Yii::t('app', 'Checkout Step2') ?><?php if (empty($hideStep2)) echo "<i
                                    class='fa fa-caret-down'></i>"; ?> </a></h4>
                    </div>
                    <?php if (!empty($modelGuest)): ?>
                    <div class="panel-collapse collapse <?php if (!empty($continueStep2)) echo $continueStep2; ?>"
                         id="collapse-payment-address" style="height: auto;">
                        <div class="panel-body">
                            <?php $form = ActiveForm::begin([
                                'type' => ActiveForm::TYPE_VERTICAL,
                                'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
                            ]);; ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <fieldset id="account">
                                        <legend><?= Yii::t('app', 'Checkout Personal') ?> </legend>
                                        <div class="form-group required">
                                            <label class="control-label"
                                                   for="input-payment-firstname"><?= Yii::t('app', 'Full Name') ?></label>
                                            <?php
                                            if (isset($modelGuest->full_name)) {
                                                echo $form->field($modelGuest, 'full_name', [
                                                    'showLabels' => false
                                                ])->textInput(['disabled' => true, 'placeholder' => Yii::t('app', 'Full Name')]);
                                            } else {
                                                echo $form->field($modelGuest, 'full_name', [
                                                    'showLabels' => false
                                                ])->textInput(['placeholder' => Yii::t('app', 'Full Name')]);
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group required">
                                            <label class="control-label"
                                                   for="input-payment-email"><?= Yii::t('app', 'Email') ?></label>
                                            <?php
                                            if ($modelGuest->email) {
                                                echo $form->field($modelGuest, 'email', [
                                                    'showLabels' => false
                                                ])->textInput(['disabled' => true, 'placeholder' => Yii::t('app', 'Email')]);
                                            } else {
                                                echo $form->field($modelGuest, 'email', [
                                                    'showLabels' => false
                                                ])->textInput(['placeholder' => Yii::t('app', 'Email')]);
                                            } ?>
                                        </div>
                                        <div class="form-group required">
                                            <label class="control-label"
                                                   for="input-payment-telephone"><?= Yii::t('app', 'Phone Number') ?></label>
                                            <?php
                                            if (isset($modelGuest->phone_number)) {
                                                echo $form->field($modelGuest, 'phone_number', [
                                                    'showLabels' => false
                                                ])->textInput(['disabled' => true, Yii::t('app', 'Phone Number')]);
                                            } else {
                                                echo $form->field($modelGuest, 'phone_number', [
                                                    'showLabels' => false
                                                ])->textInput(['placeholder' => Yii::t('app', 'Phone Number')]);
                                            } ?>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6">
                                    <fieldset id="address" class="required">
                                        <legend><?= Yii::t('app', 'Checkout Address') ?> </legend>
                                        <div class="form-group required">
                                            <label class="control-label"
                                                   for="input-payment-address-1"><?= Yii::t('app', 'CustomerAddress') ?></label>
                                            <?php
                                            if (isset($modelUpdatedAddress)) {
                                                echo $form->field($modelUpdatedAddress, 'detail', [
                                                    'showLabels' => false
                                                ])->textarea(['placeholder' => Yii::t('app', 'CustomerAddress')]);
                                            } else {
                                                echo $form->field($modelAddress, 'detail', [
                                                    'showLabels' => false
                                                ])->textarea(['placeholder' => Yii::t('app', 'CustomerAddress')]);
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group required">
                                            <label class="control-label"
                                                   for="input-payment-city"><?= Yii::t('app', 'CityLabel') ?></label>
                                            <?php
                                            if (isset($modelUpdatedAddress)) {
                                                echo $form->field($modelUpdatedCity, 'id')->dropDownList(
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
                                        <div class="form-group required">
                                            <label class="control-label"
                                                   for="input-payment-postcode"><?= Yii::t('app', 'DistrictLabel') ?></label>
                                            <?php
                                            if (isset($modelUpdatedAddress)) {
                                                echo $form->field($modelUpdatedAddress, 'district_id')->dropDownList(
                                                    \yii\helpers\ArrayHelper::map(
                                                        \common\models\District::find()
                                                            ->where(['city_id' => $modelUpdatedCity->id])
                                                            ->all(), 'id', 'name'),
                                                    [
                                                        'prompt'=>Yii::t('app', '- Chọn Quận / Huyện -'),
                                                    ]
                                                )->label(false);
                                            } else {
                                                echo $form->field($modelAddress, 'district_id')->widget(\kartik\widgets\DepDrop::classname(), [
                                                    'options'=>['prompt' => Yii::t('app', '- Chọn Quận / Huyện -')],
                                                    'pluginOptions'=>[
                                                        'depends'=>[\yii\helpers\Html::getInputId($modelCity, 'id')],
                                                        'placeholder'=>Yii::t('app', '- Chọn Quận / Huyện -'),
                                                        'url'=>\yii\helpers\Url::to(['/account/getdistrict'])
                                                    ]
                                                ])->label(false);
                                            }
                                            ?>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">

                                    <fieldset id="account">
                                        <legend><?= Yii::t('app', 'Checkout OtherInfo') ?> </legend>
                                        <div class="input-group">
                                            <div class="input-group required col-sm-6">
                                                <label class="control-label"
                                                       for="receiving_date"><?= Yii::t('app', 'Checkout ReceivingDate') ?></label>
                                                <?= $form->field($modelCheckoutInfo, 'receiving_date', [
                                                    'showLabels' => false
                                                ])->widget(\kartik\widgets\DatePicker::className(), [
                                                    'removeButton' => false,
                                                    'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                                                    'pluginOptions' => [
                                                        'autoclose' => true,
                                                        'endDate' => '+10d',
                                                        'startDate' => '-0d',
                                                        'todayHighlight' => true,
                                                    ],
                                                ]); ?>
                                            </div>
                                            <div class="input-group col-sm-6">
                                                <label class="control-label"
                                                       for="note"><?= Yii::t('app', 'Checkout Note') ?></label>
                                                <?= $form->field($modelCheckoutInfo, 'note', [
                                                    'showLabels' => false
                                                ])->textarea(['placeholder' => Yii::t('app', 'Checkout Note')]); ?>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>
                            <?php if (!Yii::$app->user->isGuest) { ?>
                                <div class="checkbox">
                                    <label>
                                        <input id="cbUpdateAddress" type="checkbox" name="updateAddress" value="FALSE">
                                        Cập nhật địa chỉ của tôi.</label>
                                </div>
                            <?php } ?>
                            <h2><?= Yii::t('app', 'ShoppingCartNotice01') ?></h2>

                            <p><?= Yii::t('app', 'ShoppingCartNotice02') ?></p>

                            <div class="panel-group" id="voucher">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a href="#collapse-coupon" class="accordion-toggle"
                                                                   data-toggle="collapse"
                                                                   data-parent="#voucher"><?= Yii::t('app', 'ShoppingCartNotice03') ?>
                                                <i class="fa fa-caret-down"></i></a></h4>
                                    </div>
                                    <div id="collapse-coupon" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="input-group">
                                                <label class="center control-label"
                                                       for="input-coupon"><?= Yii::t('app', 'VoucherRegulation') ?></label>
                                            </div>
                                            </br>
                                            <label class="col-sm-2 control-label"
                                                   for="input-coupon"><?= Yii::t('app', 'ShoppingCartNotice03') ?></label>

                                            <div class="input-group">
                                                <input type="text" name="voucher" id="voucher"
                                                       placeholder="<?= Yii::t('app', 'ShoppingCartNotice04') ?>"
                                                       class="form-control">
                                                <span class="input-group-btn">
                                                <input type="button" value="<?= Yii::t('app', 'CheckVoucherLabel') ?>"
                                                       id="button-voucher"
                                                       class="btn btn-primary">
                                                </span>
                                            </div>

                                            <div id="voucherResult"></div>
                                            <script type="text/javascript"><!--
                                                $('#cbUpdateAddress').change(function () {
                                                    cb = $(this);
                                                    cb.val(cb.prop('checked'));
                                                });
                                                $('#button-voucher').on('click', function () {
                                                    $.ajax({
                                                        url: 'index.php?r=checkout/check-voucher',
                                                        type: 'post',
                                                        data: {voucher: $('#voucher input[type=\'text\']').val()},
                                                        dataType: 'json',
                                                        complete: function () {
                                                            $('#button-voucher').button('reset');
                                                        },
                                                        success: function (data) {
                                                            $('.alert').remove();
                                                            console.log(data);
                                                            var json = $.parseJSON(data);
                                                            if (json['success']) {
                                                                $('#voucherResult').after('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>')
                                                            }
                                                            if (json['error']) {
                                                                $('#voucherResult').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                                            }
                                                        }
                                                    });
                                                });
                                                //--></script>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="buttons">
                                <div class="pull-right">
                                    <input type="submit" value="<?= Yii::t('app', 'Checkout checkout') ?>"
                                           id="button-guest"
                                           data-loading-text="Loading..." class="btn btn-primary">
                                </div>
                            </div>

                            <?php ActiveForm::end() ?>
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>



