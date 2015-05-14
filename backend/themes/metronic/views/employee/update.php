<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Employee */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Employee',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="employee-update">

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
        'ward' => $ward,
        'district' => $district,
        'city' => $city,
    ]) ?>

</div>
