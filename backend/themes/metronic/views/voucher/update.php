<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->title = 'Update Voucher: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Vouchers', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="voucher-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
