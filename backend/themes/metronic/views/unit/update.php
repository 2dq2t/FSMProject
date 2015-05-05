<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Unit */

$this->title = 'Update Unit: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Units', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="unit-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
