<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Customers');
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

<div class="customer-index">

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=>function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_details', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style']
        ],
        [
            'attribute' => 'username',
            'width' => '20%',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Customer::find()->orderBy('username')->asArray()->all(), 'username', 'username'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>Yii::t('app', 'customer username')],
            'format'=>'raw'
        ],
        [
            'attribute' => 'dob',
            'value' => function ($model) {
                return date('m/d/Y', $model->dob);
            },
            'width' => '20%',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'removeButton' => false,
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'allowClear' => true,
                    'autoclose' => true,
                    'endDate' => '+0d',
                ],
            ],
        ],
        [
            'attribute' => 'gender',
            'width' => '12%',
            'filter' => [
                'Male' =>  Yii::t('app', 'Male'),
                'Female' =>  Yii::t('app', 'Female'),
                'Other' => Yii::t('app', 'Other'),
            ],
            'format' => 'raw',
            'value' => function (\common\models\Customer $model) {
                if ($model === null || $model->gender === null) {
                    return null;
                }
                if ($model->gender === 'Male') {
                    $value = 'Male';
                } else if ($model->gender === 'Female') {
                    $value = 'Female';
                } else {
                    $value = 'Other';
                }
                return \yii\helpers\Html::tag(
                    'span',
                    Yii::t('app', $value)
                );
            },
        ],
        [
            'attribute' => 'created_at',
            'width' => '23%',
            'value' => function ($model) {
                return date('m/d/Y', $model->created_at);
            },
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'removeButton' => false,
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'allowClear' => true,
                    'autoclose' => true,
                    'endDate' => '+0d',
//                    'format'=>'yyyy-mm-dd',
                ],
            ],
        ],
        [
            'class' => \kartik\grid\EditableColumn::className(),
            'attribute' => 'status',
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
            'value' => function (\common\models\Customer $model) {
                if ($model === null || $model->status === null) {
                    return null;
                }
                if ($model->status === 1) {
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
            'width' => '11%',
            'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
        ],
    ];

    //    echo "<pre>";
    //    var_dump($dataProvider);
    //    echo "</pre>";
    //    return;

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'],
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['customer/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>