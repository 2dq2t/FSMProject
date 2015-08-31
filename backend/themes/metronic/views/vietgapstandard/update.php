<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VietgapStandard */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Vietgap Standard',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vietgap Standards'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vietgap-standard-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
