<?php

use kartik\detail\DetailView;

/* @var $model backend\models\Employee */
// DetailView Attributes Configuration
$attributes = [
    [
        'group'=>true,
        'label'=>$model->full_name,
        'rowOptions'=>['class'=>'info'],
//        'groupOptions'=>['class'=>'text-center']
    ],
    [
        'attribute'=>'image',
        'label'=>Yii::t('app', 'Image'),
//        'labelColOptions' => ['style' => 'width: 20%'],
        'value' => file_exists('uploads/employees/image/'. $model->id . '/' . $model->image)
            ? \yii\helpers\Html::img('uploads/employees/image/' . $model->id . '/' . $model->image, ['height' => '20%', 'width' => '20%']) : '',
        'inputContainer' => ['class'=>'col-sm-6'],
        'format' => 'raw',
    ],
    [
        'attribute'=>'full_name',
        'format'=>'raw',
        'value'=>$model->full_name,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'gender',
        'format'=>'raw',
        'value'=>$model->gender,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'dob',
        'format'=>'raw',
        'value'=>date('m/d/Y', $model->dob),
        'displayOnly'=>true
    ],
    [
        'attribute'=>'phone_number',
        'format'=>'raw',
        'value'=>$model->phone_number,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'email',
        'format'=>'raw',
        'value'=>$model->email,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'start_date',
        'format'=>'raw',
        'value'=>date('m/d/Y', $model->start_date),
        'displayOnly'=>true
    ],
    [
        'attribute'=>'note',
        'format'=>'raw',
        'value'=>'<span class="text-justify"><em>' . $model->note . '</em></span>',
        'type'=>DetailView::INPUT_TEXTAREA,
        'options'=>['rows'=>4]
    ],
    [
        'attribute'=>'status',
        'format'=>'raw',
        'value'=>$model->status ?
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
//    'hAlign'=>$hAlign,
//    'vAlign'=>$vAlign,
//    'fadeDelay'=>$fadeDelay,
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => 1000, 'kvdelete'=>true],
    ],
    'container' => ['id'=>'kv-demo'],
    'formOptions' => ['action' => \yii\helpers\Url::current(['#' => 'kv-demo'])] // your action to delete
]);


?>