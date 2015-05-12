<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Offer */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Offer',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offers'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="offer-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
