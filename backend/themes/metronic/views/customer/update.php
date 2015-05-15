<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Customer',
    ]) . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-update">

    <?= $this->render('_form', [
        'model' => $model,
        'guest' => $guest,
        'address' => $address,
        'ward' => $ward,
        'district' => $district,
        'city' => $city,
    ]) ?>

</div>