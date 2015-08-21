<?php

/**
 * @var $alias string
 * @var $file string
 * @var $messages string
 * @var $this \yii\web\View
 */

use kartik\helpers\Html;

$this->title = Yii::t('app', 'Update messages :{alias}', ['alias' => $alias]);
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('app', 'I18n'),
        'url' => ['index'],
    ],
    Yii::t('app', 'Update'),
];

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
                <?= Html::beginForm() ?>
                <div class="form-body">
                    <div class="form-group">
                        <?= \devgroup\jsoneditor\Jsoneditor::widget(
                            [
                                'editorOptions' => [
                                    'modes' => ['tree'],
                                ],
                                'name' => 'messages',
                                'options' => [
                                    'style' => 'height: 600px',
                                ],
                                'value' => $messages,
                            ]
                        )
                        ?>
                    </div>
                    <div class="form-group">
                        <?= Html::checkbox('ksort', Yii::$app->request->cookies->getValue('sortMessages'), ['id' => 'ksort']) ?>
                        <?= Html::label(Yii::t('app', 'Sort by source messages'), 'ksort') ?>
                    </div>
                </div>

                <div class="form-actions">
                    <div class="col-md-3 btn-set pull-right">
                        <div class="col-md-6">
                            <button type="submit" class="btn green-haze btn-circle" name="action" value="save"><i
                                    class="fa fa-check"></i> <?= Yii::t('app', 'Save')?>
                            </button>
                        </div>
                    </div>
                </div>
                <?= Html::endForm() ?>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>