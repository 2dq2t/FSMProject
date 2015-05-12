<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Offer */

$this->title = Yii::t('app', 'Create Offer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
