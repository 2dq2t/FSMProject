<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */

$this->title = $model->type == \yii\rbac\Item::TYPE_PERMISSION ? Yii::t('app', 'Create Permission') : Yii::t('app', 'Create Roles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'rules' => $rules,
        'items' => $items,
    ]) ?>

</div>
