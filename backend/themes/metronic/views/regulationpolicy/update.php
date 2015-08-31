<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RegulationPolicy */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Regulation Policy',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Regulation Policies'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="regulation-policy-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
