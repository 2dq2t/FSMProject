<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\VoucherType */

$this->title = 'Create Voucher Type';
$this->params['breadcrumbs'][] = ['label' => 'Voucher Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voucher-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
