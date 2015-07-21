<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RatingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Rating');
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

<div class="rating-index">


    <?php

    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],

        [
            'attribute' => 'rating',
            'format' => 'raw',
            'value' => function (\common\models\Rating $model) {
                if ($model === null || $model->rating === null) {
                    return null;
                }
                if ($model->rating === '0.5') {
                    $value = 'Half Star';
                } else if ($model->rating === '1') {
                    $value = 'One Star';
                } else if ($model->rating === '1.5') {
                    $value = 'One & Half Star';
                } else if ($model->rating === '2') {
                    $value = 'Two Stars';
                } else if ($model->rating === '2.5') {
                    $value = 'Two & Half Star';
                } else if ($model->rating === '3') {
                    $value = 'Three Stars';
                } else if ($model->rating === '3.5') {
                    $value = 'Four Stars';
                } else if ($model->rating === '4') {
                    $value = 'Four & Half Star';
                } else if ($model->rating === '4.5') {
                    $value = 'Four & Half Star';
                } else if ($model->rating === '5') {
                    $value = 'Five Stars';
                } else {
                    $value = 'Not Rated';
                }
                return \yii\helpers\Html::tag(
                    'span',
                    Yii::t('app', $value)
                );
            },
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>'. Yii::t('app', 'Create Rating'), ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
