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

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo lavrentiev\yii2toastr\Toastr::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'success',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'message' => (!empty($message['message'])) ? trim(preg_replace('/\s+/', ' ', $message['message'])) : 'Message Not Set!',
        'clear' => false,
        'options' => [
            "closeButton" => true,
            "positionClass" => "toast-top-right",
            "timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
        ]
    ]);
    ?>
<?php endforeach; ?>

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
                                    <?= Yii::t('app', 'Available') ?>:
                                    <input id="search-available" class="form-control" placeholder="<?= Yii::t('app', 'Filter new')?>"><br>
                                    <?php
                                    echo Html::listBox('routes', '', $new, [
                                        'id' => 'list-available',
                                        'multiple' => true,
                                        'size' => 20,
                                        'style' => 'width:100%',
                                        'class' => 'form-control',
                                    ]);
                                    ?>
                                </div>
                                <div class="col-lg-1">
                                    <br><br>
                                    <a href="javascript:" id="btn-add" class="btn btn-success">&gt;&gt;</a><br><br>
                                    <a href="javascript:" id="btn-remove" class="btn btn-danger">&lt;&lt;</a>
                                </div>
                                <div class="col-lg-5">
                                    <?= Yii::t('app', 'Assigned') ?>:
                                    <input id="search-assigned" class="form-control" placeholder="<?= Yii::t('app', 'Filter exists')?>"><br>
                                    <?php echo Html::listBox('routes', '', $exists, [
                                        'id' => 'list-assigned',
                                        'multiple' => true,
                                        'size' => 20,
                                        'style' => 'width:100%',
                                        'class' => 'form-control',
                                    ]);
                                    ?>
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
        'getRoutesUrl' => Url::to(['get-routes']),
    ]);
$js = <<<JS
yii.admin.initProperties({$properties});
$('#btn-add').click(function () {
    yii.admin.assignRoute('assign');
    return false;
});
$('#btn-remove').click(function () {
    yii.admin.assignRoute('remove');
    return false;
});
yii.admin.listFilter("#search-available", "#list-available");
yii.admin.listFilter("#search-assigned", "#list-assigned");
JS;
$this->registerJs($js);

