<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
?>
<div class="customer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelCustomer' => $modelCustomer,
        'modelGuest' => $modelGuest,
    ]) ?>

</div>
