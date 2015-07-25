<?php

use kartik\detail\DetailView;

/* @var $model common\models\Offer */
// DetailView Attributes Configuration

$images = \common\models\Image::find()->where(['product_id' => $model->id])->one()['path'];

$attributes = [
    [
        'group'=>true,
        'label'=>$model->id,
        'rowOptions'=>['class'=>'info'],
//        'groupOptions'=>['class'=>'text-center']
    ],
    [
        'attribute'=>'product_id',
        'format'=>'raw',
        'value'=>\common\models\Product::find()->where(['id' => $model->product_id])->one()['name'],
        'displayOnly'=>true
    ],
    [
        'attribute'=>'start_date',
        'format'=>'raw',
        'value'=>date('d/m/Y', $model->start_date),
        'displayOnly'=>true
    ],
    [
        'attribute'=>'end_date',
        'format'=>'raw',
        'value'=>date('d/m/Y', $model->end_date),
        'displayOnly'=>true
    ],
    [
        'attribute'=>'description',
        'format'=>'raw',
        'value'=>'<span class="text-justify"><em>' . $model->description . '</em></span>',
        'type'=>DetailView::INPUT_TEXTAREA,
        'options'=>['rows'=>4]
    ],
    [
        'attribute'=>'active',
        'format'=>'raw',
        'value'=>$model->active ?
            '<span class="label label-success">Active</span>' :
            '<span class="label label-danger">Inactive</span>',
        'type'=>DetailView::INPUT_SWITCH,
        'widgetOptions' => [
            'pluginOptions' => [
                'onText' => 'Yes',
                'offText' => 'No',
            ]
        ]
    ],
];

// View file rendering the widget
echo DetailView::widget([
    'model' => $model,
    'attributes' => $attributes,
    'mode' => 'view',
    'bordered' => true,
    'responsive' => true,
    'hover' => true,
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => 1000, 'kvdelete'=>true],
    ],
    'container' => ['id'=>'kv-demo'],
    'formOptions' => ['action' => \yii\helpers\Url::current(['#' => 'kv-demo'])] // your action to delete
]);


?>