<?php

use yii\helpers\Html;
//use mdm\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model yii\web\IdentityInterface */

$this->params['breadcrumbs'][] = $this->title;
$this->title = Yii::t('app', 'Details {modelClass}: ', [
        'modelClass' => Yii::t('app', 'Assignment'),
    ]) . $model->{$usernameField};
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Detail');
?>


<?php $this->beginBlock('submit'); ?>
<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['assignment/index'], ['class' => 'btn btn-default btn-circle']) ?>
<?php $this->endBlock('submit'); ?>


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
                            <div class="col-lg-5">
                                <?= Yii::t('app', 'Avaliable') ?>:
                                <input id="search-avaliable" class="form-control" placeholder="<?= Yii::t('app', 'Filter')?>"><br>
                                <select id="list-avaliable" class="form-control" multiple size="20" style="width: 100%">
                                </select>
                            </div>
                            <div class="col-lg-1">
                                <br><br>
                                <a href="javascript:" id="btn-assign" class="btn btn-success">&gt;&gt;</a><br><br>
                                <a href="javascript:" id="btn-revoke" class="btn btn-danger">&lt;&lt;</a>
                            </div>
                            <div class="col-lg-5">
                                <?= Yii::t('app', 'Assigned') ?>:
                                <input id="search-assigned" class="form-control" placeholder="<?= Yii::t('app', 'Filter')?>"><br>
                                <select id="list-assigned" class="form-control" multiple size="20" style="width: 100%">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="col-md-3 btn-set pull-right">
                        <?= $this->blocks['submit']?>
                    </div>
                </div>
                <?php \yii\widgets\ActiveForm::end(); ?>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>


<?php
$properties = Json::encode([
        'userId' => $model->{$idField},
        'assignUrl' => Url::to(['assign']),
        'searchUrl' => Url::to(['search']),
    ]);
$js = <<<JS
yii.admin.initProperties({$properties});

$('#search-avaliable').keydown(function () {
    yii.admin.searchAssignmet('avaliable');
});
$('#search-assigned').keydown(function () {
    yii.admin.searchAssignmet('assigned');
});
$('#btn-assign').click(function () {
    yii.admin.assign('assign');
    return false;
});
$('#btn-revoke').click(function () {
    yii.admin.assign('revoke');
    return false;
});

yii.admin.searchAssignmet('avaliable', true);
yii.admin.searchAssignmet('assigned', true);
JS;
$this->registerJs($js);

?>

