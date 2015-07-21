<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Unit */

$this->title = Yii::t('app', 'Update Unit', [
        'modelClass' => 'Unit',
    ]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Unit'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Unit');
?>
<div class="unit-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
