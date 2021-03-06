<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Employee */

$this->title = Yii::t('app', 'Create Employee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'EmployeeInfo'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">


    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
        'city' => $city,
        'item' => $item
    ]) ?>

</div>
