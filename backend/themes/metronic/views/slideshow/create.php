<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SlideShow */

$this->title = Yii::t('app', 'Create SlideShow');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Slide Shows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slide-show-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
