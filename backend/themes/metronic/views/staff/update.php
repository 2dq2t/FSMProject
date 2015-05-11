<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Staff',
]) . ' ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Staff'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="staff-update">

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
        'ward' => $ward,
        'district' => $district,
        'city' => $city,
    ]) ?>

</div>
