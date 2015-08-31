<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Recipes */

$this->title = Yii::t('app', 'Create Recipes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Recipes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipes-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
