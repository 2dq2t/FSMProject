<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Guest */

$this->title = Yii::t('app', 'Create Guest');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Guest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="guest-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
