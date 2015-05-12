<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Faq',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faqs'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="faq-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
