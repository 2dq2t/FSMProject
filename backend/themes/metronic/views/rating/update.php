<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Rating */

$this->title = Yii::t('app', 'Update Rating', [
        'modelClass' => 'Rating',
    ]) . ' ' . $model->rating;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rating'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Rating');
?>
<div class="rating-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
