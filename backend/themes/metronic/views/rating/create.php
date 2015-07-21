<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Rating */

$this->title = Yii::t('app', 'Create Rating');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Rating'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
