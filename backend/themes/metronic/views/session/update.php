<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Session */

$this->title = 'Update Session: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sessions', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="session-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
