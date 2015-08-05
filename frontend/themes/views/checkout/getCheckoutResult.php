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
    <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
        <?php
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? \yii\helpers\Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? \yii\helpers\Html::encode($message['message']) : Yii::t('app', 'Message Not Set!'),
            'showSeparator' => true,
            'delay' => 6000
        ]);
        ?>
    <?php endforeach; ?>
    <ul class="breadcrumb">
        <li><a href="<?=$baseUrl?>"><i class="fa fa-home"></i></a></li>
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
            <h1 class="page-title"><?= Yii::t('app', 'CheckoutResultLabel') ?></h1>
            <fieldset id="account">
                <legend><?= Yii::t('app', 'CheckoutResult Personal') ?> </legend>
                <div class="col-sm-12">
                    <?php if(empty($order)) {
                        echo Yii::t('app', 'CheckoutResult Fail');
                    }
                    ?>
                    <?php if(!empty($order)): ?>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult FullName')?></div>
                    <div class="col-sm-8"><?=$customer_info['full_name']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult Email')?></div>
                    <div class="col-sm-8"><?=$customer_info['email']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult PhoneNumber')?></div>
                    <div class="col-sm-8"><?=$customer_info['phone_number']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult Address')?></div>
                    <div class="col-sm-8"><?=$address['detail']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult District')?></div>
                    <div class="col-sm-8"><?=$district['name']?></div>
                    <div class="col-sm-4"><?=Yii::t('app', 'CheckoutResult City')?></div>
                    <div class="col-sm-8"><?=$city['name']?></div>
                    <?php endif?>
                </div>
            </fieldset>

        </div>
    </div>
</div>



