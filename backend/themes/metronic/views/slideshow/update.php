<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SlideShow */

$this->title = 'Update Slide Show: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Slide Shows', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="slide-show-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
