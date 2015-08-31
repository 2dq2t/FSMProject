<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\FoodPreservation */

$this->title = Yii::t('app', 'Create Food Preservation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Food Preservations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="food-preservation-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
