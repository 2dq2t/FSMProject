<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\AuthItem $model
 */
$this->title = Yii::t('app', 'Update Permission') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-item-update">

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'item' => $item
    ]);
    ?>
</div>