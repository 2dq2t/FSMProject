<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = Yii::t('app','ContactFreshGarden');
?>
<?php
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">
    <div class="row content-subinner infomation-infomation">
        <ul class="breadcrumb">
            <li style="margin-left: 10px"><a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home"></i></a></li>
            <li>
                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/contact" ?>"><?= Html::encode($this->title) ?></a>
            </li>
        </ul>
        <div class="col-sm-6">

            <p class="img-thumbnail"> <img src="images/data/nongnghiepcao.jpg " width="450px" height="400px"/>
            </p>
        </div>
        <div class="col-sm-6">
            <?php $form = ActiveForm::begin(['id' => 'contact-form','layout' => 'horizontal','class'=>'col-sm-12']); ?>
            <?= $form->field($model, 'name', [
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-9',
                ]]) ?>
            <?= $form->field($model, 'email',[
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-9',
                ]]) ?>
            <?= $form->field($model, 'subject',[
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-9',
                ]]) ?>
            <?= $form->field($model, 'body',[
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-9',
                ]])->textArea(['rows' => 6]) ?>
            <?= $form->field($model, 'verifyCode',[
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-9',
                ]])->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-sm-6">{image}</div><div class="col-sm-6">{input}</div></div>',
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','Contact Submit'), ['class' => 'btn btn-primary col-sm-offset-3', 'name' => 'contact-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="row col-sm-12">
            <div class="col-sm-12">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3724.4794664440883!2d105.52626500000001!3d21.013493!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31345bc25f79bf41%3A0x6d65a12ab66997c8!2zxJDhuqFpIGjhu41jIEZQVCBIw7JhIEzhuqFj!5e0!3m2!1svi!2s!4v1423404523615"
                    width="960" height="250" frameborder="0" style="border:0"></iframe>
                <br/>
                <small><a
                        href="https://www.google.com/maps/place/%C4%90%E1%BA%A1i+h%E1%BB%8Dc+FPT+H%C3%B2a+L%E1%BA%A1c/@21.0135922,105.5262513,17z/data=!3m1!4b1!4m2!3m1!1s0x31345bc25f79bf41:0x6d65a12ab66997c8"
                        style="color:#0000FF;text-align:left">View Larger Map</a></small>
            </div>
        </div>
    </div>
</div>
