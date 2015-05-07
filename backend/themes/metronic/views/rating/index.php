<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RatingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ratings';
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

<div class="rating-index">


    <?php

    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],

        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'rating',
            // 'value' => function($model, $key, $index, $widget) {
            //     if($model->Rating == 1.5) {
            //         return '<i class="fa fa-fw fa-folder-open"></i>';
            //     }
            // },
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_RATING,
                'header' => 'User Rating',
                'type'=>'success',
                'size'=>'md',
                'displayValueConfig'=>[
                    0=> 'Not Rated',
                    1=>'One Star',
                    1.5 => 'One & Half Star',
                    2=>'Two Stars',
                    2.5 => 'Two & Half Star',
                    3=>'Three Stars',
                    3.5 => 'Three & Half Star',
                    4=>'Four Stars',
                    4.5 => 'Four & Half Star',
                    5=>'Five Stars',
                ],
            ]
        ],

        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'description',
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            ]
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
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['rating/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Rating', ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
