<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'EmployeeInfo');
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

<div class="employee-index">

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
            'attribute' => 'full_name',
            'width' => '22%',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->orderBy('full_name')->asArray()->all(), 'full_name', 'full_name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=> Yii::t('app','Any Employee full name')],
            'format'=>'raw'
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
            'value' => function (\backend\models\Employee $model) {
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
            'attribute' => 'phone_number',
            'width' => '15%',
        ],
        [
            'attribute' => 'email',
            'width' => '20%',
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
            'value' => function (\backend\models\Employee $model) {
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
            'template' => '{update}',
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
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['employee/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
            ],
            '{toggleData}',
            '{export}',
        ],
        'pjax' => true,
        'pjaxSettings'=>[
//            'neverTimeout'=>true,
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
            'heading' => $this->title,
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '. Yii::t('app', 'Create Employee'), ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
