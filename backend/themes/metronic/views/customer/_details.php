<?php

use kartik\detail\DetailView;

/* @var $model backend\models\Employee */
// DetailView Attributes Configuration
$attributes = [
    [
        'group'=>true,
        'label'=>$model->username,
        'rowOptions'=>['class'=>'info'],
//        'groupOptions'=>['class'=>'text-center']
    ],
    [
        'attribute'=>'avatar',
        'label'=>Yii::t('app', 'Avatar'),
//        'labelColOptions' => ['style' => 'width: 20%'],
        'value' => $model->avatar && file_exists('../../frontend/web/uploads/users/avatar/'. $model->id . '/' . $model->avatar)
            ? \yii\helpers\Html::img('../../frontend/web/uploads/users/avatar/' . $model->id . '/' . $model->avatar, ['height' => '20%', 'width' => '20%']) : '',
        'inputContainer' => ['class'=>'col-sm-6'],
        'format' => 'raw',
    ],
    [
        'attribute'=>'guest_id',
        'label' => Yii::t('app', 'Full Name'),
        'value'=>common\models\Guest::find()->where(['id' => $model->guest_id])->one()['full_name'],
        'displayOnly'=>true
    ],
    [
        'attribute'=>'guest_id',
        'label' => Yii::t('app', 'Email'),
        'value'=>common\models\Guest::find()->where(['id' => $model->guest_id])->one()['email'],
        'displayOnly'=>true
    ],
    [
        'attribute'=>'guest_id',
        'label' => Yii::t('app', 'Phone Number'),
        'value'=>common\models\Guest::find()->where(['id' => $model->guest_id])->one()['phone_number'],
        'displayOnly'=>true
    ],
    [
        'attribute'=>'dob',
        'format'=>'raw',
        'value'=>date('m/d/Y', $model->dob),
        'displayOnly'=>true
    ],
    [
        'attribute'=>'gender',
        'format'=>'raw',
        'value'=>$model->gender,
        'displayOnly'=>true
    ],
    [
        'attribute'=>'created_at',
        'format'=>'raw',
        'value'=>date('m/d/Y H:i:s', $model->created_at),
        'displayOnly'=>true
    ],
    [
        'attribute'=>'updated_at',
        'format'=>'raw',
        'value'=> $model->updated_at ? date('m/d/Y H:i:s', $model->updated_at) : Yii::t('app', 'Not set'),
        'displayOnly'=>true
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
    'deleteOptions'=>[ // your ajax delete parameters
        'params' => ['id' => 1000, 'kvdelete'=>true],
    ],
    'container' => ['id'=>'kv-demo'],
    'formOptions' => ['action' => \yii\helpers\Url::current(['#' => 'kv-demo'])] // your action to delete
]);


?>