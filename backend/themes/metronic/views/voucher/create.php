<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->title = Yii::t('app', 'Create Voucher');
$this->params['breadcrumbs'][] = ['label' => 'Vouchers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voucher-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
