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
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
            'delay' => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
            'showSeparator' => true,
            'options' => ['format' => 'raw']
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="order-index">

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'customer_name',
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
            'width' => '23%',
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
            'attribute' => 'receiving_date',
            'width' => '23%',
            'value' => function ($model) {
                return date('m/d/Y', $model->receiving_date);
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
            'attribute' => 'order_status',
            'filter' => \yii\helpers\ArrayHelper::map(\backend\models\OrderStatus::find()->all(), 'name', 'name'),
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'width' => '11%',
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success']),
        ],
    ]); ?>

</div>
