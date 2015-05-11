<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Guest */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Guest',
    ]) . ' ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Guests'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="guest-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
