<?php
use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * @var $id string
 * @var $data \yii\data\ArrayDataProvider
 * @var $isRules bool
 */
?>

<?php

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'attribute' => 'name',
    ],
    [
        'attribute' => 'description',
    ],
    [
        'attribute' => 'rule_name',
    ],
    [
        'attribute' => 'created_at',
        'value' => function ($model) {
            return date('m/d/Y', $model->created_at);
        },
    ],
    [
        'attribute' => 'updated_at',
        'value' => function ($model) {
            return date('m/d/Y', $model->updated_at);
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
//        'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
        'buttons' => [
            [
                'url' => 'update',
                'icon' => 'pencil',
                'class' => 'btn-primary',
                'label' => Yii::t('app', 'Edit'),
            ],
            [
                'url' => 'delete',
                'icon' => 'trash-o',
                'class' => 'btn-danger',
                'label' => Yii::t('app', 'Delete'),
            ],
        ],
        'urlCreator' => function ($action, $model, $key, $index) {
            $params = is_array($key) ? $key : ['id' => (string)$key];
            if ($action != 'delete') {
                $params['type'] = $model->type;
            }
            $params[0] = $this->context->id ? $this->context->id . '/' . $action : $action;
            return \yii\helpers\Url::toRoute($params);
        },
    ],
];

?>

<?= GridView::widget([
    'id' => $id,
    'dataProvider' => $data,
//        'filterModel' => $searchModel,
    'columns' => $gridColumns,
    'containerOptions' => ['style'=>'overflow: auto'],
    'toolbar' =>  [
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['authitem/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
        ],
        '{toggleData}',
        '{export}',
    ],
    'pjax' => true,
    'pjaxSettings'=>[
        'neverTimeout'=>true,
    ],
    'bordered' => true,
    'striped' => false,
    'condensed' => false,
    'responsive' => true,
    'hover' => true,
    'resizableColumns' => true,
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => $this->title,
        'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Create Permission'), ['create'], ['class' => 'btn btn-success']),
    ],
]); ?>