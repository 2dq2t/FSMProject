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
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay' => 3000
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="order-index">

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'user_id',
            'value' => 'user.fullname',
            'label' => 'Customer',
//            'width' => '20%',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Guest::find()->orderBy('full_name')->asArray()->all(), 'full_name', 'full_name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>Yii::t('app', 'customer username')],
            'format'=>'raw'
        ],
        [
            'attribute' => 'order_status_id',
            'filter'=>\yii\helpers\ArrayHelper::map(\backend\models\OrderStatus::find()->orderBy('title')->asArray()->all(), 'title', 'title'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
        ],
        [
            'attribute' => 'order_date',
            'width' => '23%',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'removeButton' => false,
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'allowClear' => true,
                    'autoclose' => true,
                    'endDate' => '+0d',
                    'format'=>'yyyy-mm-dd',
                ],
            ],
        ],
        [
            'attribute' => 'receiving_date',
            'width' => '23%',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'removeButton' => false,
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'allowClear' => true,
                    'autoclose' => true,
                    'endDate' => '+0d',
                    'format'=>'yyyy-mm-dd',
                ],
            ],
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
//            [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
//            'order_date',
//            'receiving_date',
//            'shipping_fee',
//            'discount',
//            // 'tax_amount',
//            // 'net_amount',
//            // 'description:ntext',
//            // 'user_id',
//            // 'voucher_id',
//            // 'address_id',
//            // 'order_status_id',
//
//            ['class' => 'yii\grid\ActionColumn'],
//        ],
    ]); ?>

</div>
