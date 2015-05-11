<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OrderStatus */

$this->title = Yii::t('app', 'Create Order Status');
$this->params['breadcrumbs'][] = ['label' => 'Order Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-status-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
