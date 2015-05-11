<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = Yii::t('app', 'Create Staff');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Staff'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-create">

    <?= $this->render('_form', [
        'model' => $model,
        'address' => $address,
        'ward' => $ward,
        'district' => $district,
        'city' => $city,
    ]) ?>

</div>
