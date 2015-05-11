<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VoucherType */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Voucher Type',
    ]) . ' ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Voucher Type'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="voucher-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
