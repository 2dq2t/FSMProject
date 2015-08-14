<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\AuthItemSearch $searchModel
 */
$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo lavrentiev\yii2toastr\Toastr::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'success',
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'message' => (!empty($message['message'])) ? trim(preg_replace('/\s+/', ' ', $message['message'])) : 'Message Not Set!',
            'options' => [
                "closeButton" => true,
                "positionClass" => "toast-top-right",
                "timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="role-index">

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'name',
            'label' => Yii::t('app', 'Role Name'),
        ],
        [
            'attribute' => 'description',
            'label' => Yii::t('app', 'Role Description'),
        ],
        [
            'attribute' => 'created_at',
            'label' => Yii::t('app', 'Role Created At'),
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
            'label' => Yii::t('app', 'Role Updated At'),
            'value' => function ($data) {
                return date('d/m/Y', $data->updated_at);
            },
            'width' => '20%',
            'filter'=>false
//            'filterType' => GridView::FILTER_DATE,
//            'filterWidgetOptions' => [
//                'removeButton' => false,
//                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
//                'pluginOptions' => [
//                    'allowClear' => true,
//                    'autoclose' => true,
//                    'endDate' => '+1d',
//                ],
//            ],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update}&nbsp;&nbsp;{delete}',
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
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['role/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
            ],
            '{toggleData}',
            '{export}',
        ],
        'pjax' => true,
        'pjaxSettings'=>[
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '. Yii::t('app', 'Create Role'), ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
