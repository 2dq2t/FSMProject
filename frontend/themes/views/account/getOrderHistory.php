<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 21/08/2015
 * Time: 3:09 CH
 */
use kartik\grid\GridView;
$this->title = Yii::t('app','MyOrderLabel');
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=account/get-order-history"?>"><?=Yii::t('app','MyOrderLabel')?></a>
        </li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php
            echo $this->render('/layouts/_category.php');
            echo $this->render('/layouts/_leftBanner');
            echo $this->render('/layouts/_specialProduct.php');
            echo $this->render('/layouts/_bestSeller.php');
            ?>
        </column>
        <div id="content" class="col-sm-9 categorypage">


            <?php
            $gridColumns = [
                [
                    'class'=>'kartik\grid\SerialColumn',
                    'header'=>'',
                ],
                [
                    'class'=>'kartik\grid\ExpandRowColumn',
                    'width'=>'50px',
                    'value'=>function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail'=>function ($model, $key, $index, $column) {
                        return Yii::$app->controller->renderPartial('_orderDetail', ['model'=>$model]);
                    },
                    'headerOptions'=>['class'=>'kartik-sheet-sty']
                ],
                [
                    'attribute' => 'full_name',
                    'label' => Yii::t('app', 'CustomerLabel'),
                    'width' => '20%',
                    'format'=>'raw'
                ],
                [
                    'attribute' => 'order_date',
                    'label' => Yii::t('app', 'OrderDate'),
                    'width' => '18%',
                    'value' => function ($model) {
                        return date('d/m/Y', $model->order_date);
                    },
                ],
                [
                    'label' => Yii::t('app', 'CustomerAddress'),
                    'attribute' => 'address',
                    'width' => '30%',
                ],
                [
                    'attribute' => 'order_status_id',
                    'label' => Yii::t('app', 'OrderStatusID'),
                    'width' => '23%',
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
            ];

            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'containerOptions' => ['style'=>'overflow: auto'],
                'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                'pjax' => true,
                'pjaxSettings'=>[
                    'neverTimeout'=>true,
                    'options' => [
                        'enablePushState' => false
                    ],
                ],
                'options' => [
                    'id' => 'order_gridview'
                ],
                'beforeHeader'=>[
                    [
                        'options'=>['class'=>'skip-export'] // remove this row from export
                    ]
                ],
                'bordered'=>true,
                'striped' => false,
                'condensed' => false,
                'responsive' => true,
                'hover' => true,
                'resizableColumns' => true,
                'panel' => [
                    'type' => GridView::TYPE_SUCCESS,
                    'heading' => $this->title,
                    'options'=>['style'=>'color:white']
                ],
            ]); ?>
        </div>
    </div>
</div>
