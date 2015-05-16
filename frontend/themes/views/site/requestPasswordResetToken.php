<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

?>
<div class="container">
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
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'form-request-reset', 'method' => 'post', 'options' => ['class' => 'login-form cf-style-1']]); ?>
        <div class="col-md-6">
            <section class="section register inner-right-xs">
                <p>Bạn hãy điền địa chỉ Email, chúng tôi sẽ gửi thông tin thay đổi đến đó.</p>
                <div class="field-row">
                    <label>Email</label>
                    <?= $form->field($model, 'email', [
                        'inputOptions' => [
                            'class' => 'le-input',
                            'maxlength' => 255,
                        ],
                    ])->label(false); ?>
                </div><!-- /.field-row -->
                <div class="buttons-holder">
                    <button type="submit" class="le-button huge" name="submit">Gửi Email</button>
                </div><!-- /.buttons-holder -->
            </section>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<footer id="footer" class="color-bg">