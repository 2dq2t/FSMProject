<?php

use kartik\detail\DetailView;

/* @var $model common\models\Product */
// DetailView Attributes Configuration

$images = \common\models\Image::find()->where(['product_id' => $model->id])->one()['path'];

$attributes = [
    [
        'group'=>true,
        'label'=>$model->name,
        'rowOptions'=>['class'=>'info'],
//        'groupOptions'=>['class'=>'text-center']
    ],
    [
//        'attribute'=>'images.name',
        'label'=>Yii::t('app', 'Image'),
//        'labelColOptions' => ['style' => 'width: 20%'],
        'value' => file_exists('../../frontend/web/' . $images)
            ? \yii\helpers\Html::img('../../frontend/web/' . $images, ['height' => '20%', 'width' => '20%']) : '',
        'inputContainer' => ['class'=>'col-sm-6'],
        'format' => 'raw',
    ],
    [
        'attribute'=>'barcode',
        'format'=>'raw',
        'value'=>$model->barcode,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'name',
        'format'=>'raw',
        'value'=>$model->name,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'price',
        'format'=>'raw',
        'value'=>$model->price,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'quantity',
        'format'=>'raw',
        'value'=>$model->quantity,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'sold',
        'format'=>'raw',
        'value'=>$model->sold,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'fee',
        'format'=>'raw',
        'value'=>$model->fee,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'tag',
        'format'=>'raw',
        'value'=>$model->tag,
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