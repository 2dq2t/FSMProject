<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var backend\models\AuthItem $model
 */
$this->title = Yii::t('app', 'View Role: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginBlock('submit'); ?>
<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['role/index'], ['class' => 'btn btn-default btn-circle']) ?>

<?= Html::a('<i class="fa fa-check"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->name] ,[
    'class' => 'btn green-haze btn-circle'
]) ?>

<?= Html::a('<i class="fa fa-check"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id'=>$model->name], [
    'class' => 'btn red-haze btn-circle',
    'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
    'data-method' => 'post'
]) ?>
<?php $this->endBlock('submit'); ?>
<div class="auth-item-view">

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i><?= $this->title?>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title="">
                        </a>
                        <a href="javascript:;" class="remove" data-original-title="" title="">
                        </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <?php
                                echo DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        'name',
                                        'description:ntext',
                                        'rule_name',
                                        'data:ntext',
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <div class="col-md-12">-->
<!--                                <div class="col-lg-5">-->
<!--                                    --><?//= Yii::t('app', 'Avaliable') ?><!--:-->
<!--                                    <input id="search-avaliable" class="form-control" placeholder="--><?//= Yii::t('app', 'Filter')?><!--"><br>-->
<!--                                    <select id="list-avaliable" class="form-control" multiple size="20" style="width: 100%">-->
<!--                                    </select>-->
<!--                                </div>-->
<!--                                <div class="col-lg-1">-->
<!--                                    <br><br>-->
<!--                                    <a href="javascript:" id="btn-add" class="btn btn-success">&gt;&gt;</a><br><br>-->
<!--                                    <a href="javascript:" id="btn-remove" class="btn btn-danger">&lt;&lt;</a>-->
<!--                                </div>-->
<!--                                <div class="col-lg-5">-->
<!--                                    --><?//= Yii::t('app', 'Assigned') ?><!--:-->
<!--                                    <input id="search-assigned" class="form-control" placeholder="--><?//= Yii::t('app', 'Filter')?><!--"><br>-->
<!--                                    <select id="list-assigned" class="form-control" multiple size="20" style="width: 100%">-->
<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-6 col-md-6">
                                <?= $this->blocks['submit']?>
                            </div>
                        </div>
                    </div>
                    <!-- END FORM-->
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
</div>
<?php
//$properties = Json::encode([
//        'roleName' => $model->name,
//        'assignUrl' => Url::to(['assign']),
//        'searchUrl' => Url::to(['search']),
//    ]);
//$js = <<<JS
//yii.admin.initProperties({$properties});
//
//$('#search-avaliable').keydown(function () {
//    yii.admin.searchRole('avaliable');
//});
//$('#search-assigned').keydown(function () {
//    yii.admin.searchRole('assigned');
//});
//$('#btn-add').click(function () {
//    yii.admin.addChild('assign');
//    return false;
//});
//$('#btn-remove').click(function () {
//    yii.admin.addChild('remove');
//    return false;
//});
//
//yii.admin.searchRole('avaliable', true);
//yii.admin.searchRole('assigned', true);
//JS;
//$this->registerJs($js);

