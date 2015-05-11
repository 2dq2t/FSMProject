<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VoucherType */

$this->title = 'Update Voucher Type: ' . ' ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Voucher Types', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="voucher-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
