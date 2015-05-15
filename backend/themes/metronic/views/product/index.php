<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
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
//                // 'placement' => [
//                //     'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
//                //     'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
//                // ]
//            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="product-index">

    <?php
    $min = 1;
    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],

        // [
        //     'class' => 'kartik\grid\DataColumn',
        //     'attribute' => 'Id'
        // ],
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
            //'disabled'=>true,
            //'detailUrl'=>Url::to(['/site/test-expand'])
        ],
//        [
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'barcode',
//            'editableOptions' => [
//                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
//            ]
//        ],
        [
            'attribute' => 'name',
            'value'=>function ($model, $key, $index, $widget) {
                return Html::a($model->name,
                    '#',
                    ['title'=>'View product detail', 'onclick'=>'alert("This will open the product page!")']);
            },
            'pageSummary'=>'Total',
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Product::find()->orderBy('name')->asArray()->all(), 'name', 'name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>Yii::t('app', 'Any product')],
            'format'=>'raw'
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'price',
            'width' => '15%',
            'pageSummary' => true,
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            ]
        ],
        [
            'attribute' => 'season_id',
            'label' => Yii::t('app', 'Season'),
            'value' => 'season.name',
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\Season::find()->all(), 'id', 'name'),
            'width' => '15%',
            'pageSummary' => true,
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'quantity',
            'width' => '15%',
            'pageSummary' => true,
            'editableOptions'=>
                function ($model, $key, $index) {
                    return [
                        'header' => '&nbsp;',
                        'size' => 'md',
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'pluginEvents' => [ ],
                    ];
                }
//                [

//                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
//            ]
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
            'value' => function (\common\models\Product $model) {
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
            'width' => '11%',
            'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
//            'buttons' => [
//                'update' => function ($url,$model) {
//                    return Html::a(
//                        '<span class="fa fa-pencil btn btn-primary"></span>', $url, ['title' => Yii::t('yii', 'Edit'),'id' => 'modalLink', 'onclick'=>'javascript:void(0)', 'value'=>$url]);
//                },
//                'delete' => function ($url,$model) {
//                    return Html::a(
//                        '<span class="fa fa-trash btn btn-danger"></span>', $url, ['title' => Yii::t('yii', 'Delete'), 'id' => 'modalLink', 'onclick'=>'javascript:void(0)', 'value'=>$url]);
//                }
//            ],
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
            // Html::button('&lt;i class="glyphicon glyphicon-plus">&lt;/i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['product/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Create Product'), ['create'], ['class' => 'btn btn-success']),
        ],
        'showPageSummary' => true,
    ]); ?>

</div>