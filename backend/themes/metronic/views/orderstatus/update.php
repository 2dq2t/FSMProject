<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderStatus */

$this->title = Yii::t('app', 'Update Order Status', [
        'modelClass' => 'Order Status',
    ]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Order Status');
?>
<div class="order-status-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
