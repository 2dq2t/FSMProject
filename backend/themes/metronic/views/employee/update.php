<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Employee */

$this->title = Yii::t('app', 'Update Employee', [
    'modelClass' => 'Employee',
]) . ' ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'EmployeeInfo'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Employee');
?>
<div class="employee-update">

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
        'city' => $city,
    ]) ?>

</div>
