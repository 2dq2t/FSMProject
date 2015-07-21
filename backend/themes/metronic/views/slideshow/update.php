<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SlideShow */

$this->title = Yii::t('app', 'Update SlideShow', [
        'modelClass' => 'Slide Show',
    ]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Slide Show'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update SlideShow');
?>
<div class="slide-show-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
