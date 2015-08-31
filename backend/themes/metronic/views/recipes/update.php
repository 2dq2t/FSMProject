<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Recipes */

$this->title = Yii::t('app', 'Update Recipes: ') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Recipes'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="recipes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
