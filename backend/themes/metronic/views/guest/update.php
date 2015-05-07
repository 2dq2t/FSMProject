<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Guest */

$this->title = 'Update Customer: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Guests', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="guest-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
