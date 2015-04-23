<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo \kartik\widgets\Growl::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay' => 1, //This delay is how long before the message shows
            'pluginOptions' => [
                'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                // 'placement' => [
                //     'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                //     'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                // ]
            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="product-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--  <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?php

    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],

        // [
        //     'class' => 'kartik\grid\DataColumn',
        //     'attribute' => 'Id'
        // ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'Barcode',
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            ]
        ],

        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'Name',
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            ]
        ],

        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'SellPrice',
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            ]
        ],

        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'Total',
            'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            ]
        ],

        [
            'class' => \kartik\grid\EditableColumn::className(),
            'attribute' => 'Active',
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
            'value' => function (\backend\models\Product $model) {
                if ($model === null || $model->Active === null) {
                    return null;
                }
                if ($model->Active === 1) {
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
                }
            ],
        ],

        [
            'class'=>'kartik\grid\CheckboxColumn',
            'headerOptions'=>['class'=>'kartik-sheet-style'],
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Product', ['create'], ['class' => 'btn btn-success']),
        ],
        'showPageSummary' => true,
    ]); ?>

</div>
