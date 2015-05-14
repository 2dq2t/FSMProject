<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Units');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay' => 3000, //This delay is how long before the message shows
//            'pluginOptions' => [
//                'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
//                 'placement' => [
//                     'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
//                     'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
//                 ]
//            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="unit-index">

    <?php

    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],

        [
            'attribute' => 'name',
        ],
        [
            'class' => \kartik\grid\EditableColumn::className(),
            'attribute' => 'active',
            'width' => '11%',
            'editableOptions' => [
                'data' => [
                    0 =>  Yii::t('app', 'Inactive'),
                    1 =>  Yii::t('app', 'Active'),
                ],
                'inputType' => 'dropDownList',
                'placement' => 'left'
            ],
            'filter' => [
                0 =>  Yii::t('app', 'Inactive'),
                1 =>  Yii::t('app', 'Active'),
            ],
            'format' => 'raw',
            'value' => function (\common\models\Unit $model) {
                if ($model === null || $model->active === null) {
                    return null;
                }
                if ($model->active === 1) {
                    $label_class = 'label-success';
                    $value = 'Active';
                } else {
                    $value = 'Inactive';
                    $label_class = 'label-default';
                }
                return \yii\helpers\Html::tag(
                    'span',
                    Yii::t('app', $value),
                    ['class' => "label $label_class"]
                );
            },
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
            'buttons' => [
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>', $url, ['id' => 'modalLink', 'onclick'=>'javascript:void(0)', 'value'=>$url]);
                },
            ],
        ],

//        [
//            'class'=>'kartik\grid\CheckboxColumn',
//            'headerOptions'=>['class'=>'kartik-sheet-style'],
//        ],
    ];

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'],
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['unit/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Unit', ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
