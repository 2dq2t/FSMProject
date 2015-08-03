<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 */
$this->title = Yii::t('app', 'Routes');
$this->params['breadcrumbs'][] = $this->title;
?>

<div>

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
                            <div class="panel-heading">
                                <?= Html::a(Yii::t('app', 'Create route'), ['create'], ['class' => 'btn btn-success']) ?>
                            </div>
                            <div class="col-md-12">
                                <div class="col-lg-5">
                                    <?= Yii::t('app', 'Avaliable') ?>:
                                    <div class="input-group">
                                    <input id="search-avaliable" class="form-control" placeholder="<?= Yii::t('app', 'Filter')?>">
                                    <a href="#" id="btn-refresh" class="input-group-addon"><span class="glyphicon glyphicon-refresh"></span></a></div><br>
                                    <select id="list-avaliable" class="form-control" multiple size="20" style="width: 100%">
                                    </select>
                                </div>
                                <div class="col-lg-1">
                                    <br><br>
                                    <a href="javascript:" id="btn-add" class="btn btn-success">&gt;&gt;</a><br><br>
                                    <a href="javascript:" id="btn-remove" class="btn btn-danger">&lt;&lt;</a>
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
                    <?php \yii\widgets\ActiveForm::end(); ?>
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
$properties = Json::encode([
        'assignUrl' => Url::to(['assign']),
        'searchUrl' => Url::to(['search']),
    ]);
$js = <<<JS
yii.admin.initProperties({$properties});

$('#search-avaliable').keydown(function () {
    yii.admin.searchRoute('avaliable');
});
$('#search-assigned').keydown(function () {
    yii.admin.searchRoute('assigned');
});
$('#btn-add').click(function () {
    yii.admin.assignRoute('assign');
    return false;
});
$('#btn-remove').click(function () {
    yii.admin.assignRoute('remove');
    return false;
});
$('#btn-refresh').click(function () {
    yii.admin.searchRoute('avaliable',1);
    return false;
});

yii.admin.searchRoute('avaliable', 0, true);
yii.admin.searchRoute('assigned', 0, true);
JS;
$this->registerJs($js);

