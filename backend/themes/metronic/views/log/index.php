<?php

use \Yii;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Logs');
$this->params ['breadcrumbs'] [] = ['label' => Yii::t('app', 'Logs')];
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

<div class="log-index">
    <div class="row">
        <div class="col-md-12">
            <?php
            echo GridView::widget([
                'id' => 'install-grid',
                'export' => false,
                'dataProvider' => $dataProvider,
                'resizableColumns' => false,
                'responsive' => true,
                'hover' => true,
                'panel' => [
                    'heading' => '<h3 class="panel-title"> ' . Yii::t('app', 'Logs') . '</h3>',
                    'type' => 'primary',
                    'showFooter' => false
                ],
                'columns' => array(
                    ['class' => 'kartik\grid\SerialColumn'],
                    'name',
                    'size:size',
                    'create_time',
                    'modified_time:relativeTime',
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{restore_action}',
                        'header' => Yii::t('app', 'View log'),
                        'buttons' => [
                            'restore_action' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'View this backup'),
                                ]);
                            }
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'restore_action') {
                                $url = Yii::$app->urlManager->createUrl(array('log/view', 'filename' => $model['name']));
                                return $url;
                            }
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{download_action}',
                        'header' => Yii::t('app', 'Download'),
                        'buttons' => [
                            'download_action' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-download"></span>', $url, [
                                    'title' => Yii::t('app', 'Download this log'),
                                ]);
                            }
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'download_action') {
                                $url = Yii::$app->urlManager->createUrl(array('log/download', 'filename' => $model['name']));
                                return $url;
                            }
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{delete_action}',
                        'header' => 'Delete file',
                        'buttons' => [
                            'delete_action' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('app', 'Delete this log'),
                                    'data-confirm' => Yii::t('app', 'Are you sure delete this log?')
                                ]);
                            }
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'delete_action') {
                                $url = Yii::$app->urlManager->createUrl(array('log/delete', ['filename' => $model['name']]));
                                return $url;
                            }
                        }
                    ],
                ),
            ]);
            ?>
        </div>
    </div>
</div>
