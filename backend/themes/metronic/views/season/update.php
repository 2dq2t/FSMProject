<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Season */

$this->title = Yii::t('app', 'Update Season', [
    'modelClass' => 'Season',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Seasons'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Season');
?>
<div class="season-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
