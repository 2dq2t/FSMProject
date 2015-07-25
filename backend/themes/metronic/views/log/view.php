<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'View log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo lavrentiev\yii2toastr\Toastr::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'success',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
        'message' => (!empty($message['message'])) ? $message['message'] : Yii::t('app', 'Message Not Set!'),
        'clear' => false,
        'options' => [
            "closeButton" => true,
            "positionClass" => "toast-top-right",
            "timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
        ]
    ]);
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">

    <?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['log/index'], ['class' => 'btn btn-default btn-circle']) ?>

    <?= Html::a('<i class="fa fa-download"></i> ' . Yii::t('app', 'Download'),['log/download', 'filename' => $filename], ['class' => 'btn green-haze btn-circle', 'name' => 'action']) ?>

</div>
<?php $this->endBlock('submit'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i><?= $this->title ?>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                    </a>
                    <a href="javascript:;" class="remove">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?= Html::beginForm('','get') ?>
                <div class="form-body">
                    <div class="form-group">
                        <pre class="prettyprint linenums"><?php
                                while(($buffer = fgets($context)) !== false) {
                                    echo $buffer;
                                }
                                fclose($context);
                            ?>
                        </pre>
                    </div>
                </div>

                <div class="form-actions">
                    <div class="col-md-3 btn-set pull-right">
                            <?php echo $this->blocks['submit']; ?>
                    </div>
                </div>
                <?= Html::endForm() ?>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
