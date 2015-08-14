<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ArrayDataProvider
 */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'I18n');
$this->params['breadcrumbs'][] = $this->title;

?>


<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo lavrentiev\yii2toastr\Toastr::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'success',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : Yii::t('app', 'Title Not Set!'),
        'message' => (!empty($message['message'])) ? $message['message'] : Yii::t('app', 'Message Not Set!'),
        'clear' => false,
        'options' => [
            "closeButton" => true,
            "positionClass" => "toast-top-right",
            "timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
        ]
    ]);
    ?>
<?php endforeach; ?>

<div>

    <?php
    $gridColumns = [
        [
            'label' => Yii::t('app', 'Alias'),
            'value' => function($model, $key, $index, $column) {
                return $key;
            },
        ],
        [
            'label' => Yii::t('app', 'Local file'),
            'value' => function($model, $key, $index, $column) {
                return $model;
            },
        ],
        [
            'class' => \kartik\grid\ActionColumn::className(),
            'options' => [
                'width' => '50px',
            ],
            'template' => '{update}',
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'],
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['i18n/index'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
            ],
            '{toggleData}',
            '{export}',
        ],
        'pjax' => true,
//        'pjaxSettings'=>[
//            'neverTimeout'=>true,
//        ],
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'resizableColumns' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => $this->title,
        ],
    ])

    ?>
</div>