<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */

$this->title = Yii::t('app', 'Update Permission', [
    'modelClass' => 'Auth Item',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Permission');
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'rules' => $rules,
        'items' => $items,
    ]) ?>

</div>
