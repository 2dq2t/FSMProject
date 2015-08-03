<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\AuthItemSearch */
/* @var $model backend\models\\AuthItem*/

$this->title = Yii::t('app', 'Permission');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php

        echo lavrentiev\yii2toastr\Toastr::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'success',
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'message' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
            'clear' => false,
            'options' => [
                "closeButton" => true,
                "positionClass" => "toast-top-right",
                "timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="permission-index">

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'name',
            'label' => Yii::t('app', 'PermissionName'),
        ],
        [
            'attribute' => 'description',
            'label' => Yii::t('app', 'PermissionDescription'),
        ],
        [
            'attribute' => 'created_at',
            'label' => Yii::t('app', 'Permission Created At'),
            'value' => function ($data) {
                return date('d/m/Y', $data->created_at);
            },
            'width' => '20%',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'removeButton' => false,
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'allowClear' => true,
                    'autoclose' => true,
                    'endDate' => '+1d',
                ],
            ],
        ],
        [
            'attribute' => 'updated_at',
            'label' => Yii::t('app', 'Permission Updated At'),
            'value' => function ($data) {
                return date('d/m/Y', $data->updated_at);
            },
            'width' => '20%',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'removeButton' => false,
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'allowClear' => true,
                    'autoclose' => true,
                    'endDate' => '+1d',
                ],
            ],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'urlCreator' => function ($action, $model, $key, $index) {
                $params = ['id' => $model->name];
                $params[0] = $this->context->id ? $this->context->id . '/' . $action : $action;
                return \yii\helpers\Url::toRoute($params);
            },
        ],
    ];

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'],
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['permission/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
            ],
            '{toggleData}',
            '{export}',
        ],
        'pjax' => true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'options' => [
                'enablePushState' => false
            ],
        ],
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'resizableColumns' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '. Yii::t('app', 'Create Permission'), ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
