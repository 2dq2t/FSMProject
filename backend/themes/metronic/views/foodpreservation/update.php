<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FoodPreservation */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Food Preservation',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Food Preservations'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="food-preservation-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
