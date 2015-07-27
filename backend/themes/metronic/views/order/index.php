<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
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
<div class="order-index">

    <?php
    $gridColumns = [
        [
            'class'=>'kartik\grid\SerialColumn',
            'header'=>'',
        ],
        [
            'class'=>'kartik\grid\CheckboxColumn',
        ],
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
            'attribute' => 'order_id'
        ],
        [
            'attribute' => 'full_name',
            'label' => Yii::t('app', 'Customer Name'),
            'width' => '20%',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Guest::find()->orderBy('full_name')->asArray()->all(), 'full_name', 'full_name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>Yii::t('app', 'customer name')],
            'format'=>'raw'
        ],
        [
            'attribute' => 'order_date',
            'width' => '15%',
            'value' => function ($model) {
                return date('m/d/Y', $model->order_date);
            },
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'removeButton' => false,
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'allowClear' => true,
                    'autoclose' => true,
                ],
            ],
        ],
        [
            'attribute' => 'address',
            'width' => '20%',
        ],
        [
            'attribute' => 'order_status_id',
            'width' => '20%',
            'filter' => \yii\helpers\ArrayHelper::map(\backend\models\OrderStatus::find()->all(), 'id', 'name'),
            'format' => 'raw',
            'value' => function (\backend\models\OrderView $model) {
                if ($model === null) {
                    return null;
                }
                if ($model->order_status_id === 1) {
                    $label_class = 'label-info';
                    $value = \backend\models\OrderStatus::find()->where(['id' => $model->order_status_id])->one()['name'];
                } else if ($model->order_status_id == 2) {
                    $label_class = 'label-primary';
                    $value = \backend\models\OrderStatus::find()->where(['id' => $model->order_status_id])->one()['name'];
                } else if ($model->order_status_id == 3) {
                    $label_class = 'label-danger';
                    $value = \backend\models\OrderStatus::find()->where(['id' => $model->order_status_id])->one()['name'];
                } else {
                    $label_class = 'label-success';
                    $value = \backend\models\OrderStatus::find()->where(['id' => $model->order_status_id])->one()['name'];
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
            'template' => '{confirm}{delivered}{cancel}',
            'buttons' => [
                'confirm' => function ($url, $model) {
                    return $model->order_status_id <> 2 && $model->order_status_id <> 4 ? Html::a('<span class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ' . Yii::t('app', 'Confirm order') . '</span>',
                            ['order/confirm', 'id' => $model->order_id],
                            [
                                'data-method' => 'post',
                                'title' => Yii::t('app', 'Confirm'),
                            ]). '&nbsp;<br/>&nbsp;<br/>' : '';
                },
                'delivered' => function ($url, $model) {
                    return $model->order_status_id <> 3 && $model->order_status_id <> 1 && $model->order_status_id <> 4 ? Html::a('<span class="btn btn-sm btn-success"><i class="fa fa-check"></i> ' . Yii::t('app', 'Delivered order') . '</span>',
                            ['order/delivered', 'id' => $model->order_id],
                            [
                                'data-method' => 'post',
                                'title' => Yii::t('app', 'Delivered'),
                            ]). '&nbsp;<br/>' : '';
                },
                'cancel' => function ($url, $model) {
                    return $model->order_status_id <> 3 && $model->order_status_id <> 4 ? Html::a('<span class="btn btn-sm btn-warning"><i class="fa fa-check"></i> ' . Yii::t('app', 'Cancel order') . '</span>',
                        ['order/cancel', 'id' => $model->order_id],
                        [
                            'data-method' => 'post',
                            'title' => Yii::t('app', 'Cancel'),
                        ]) : '';
                }
            ]
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
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['order/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
