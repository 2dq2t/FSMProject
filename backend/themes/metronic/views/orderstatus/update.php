<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderStatus */

$this->title = 'Update Order Status: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Order Statuses', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-status-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
