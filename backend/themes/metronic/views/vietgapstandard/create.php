<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VietgapStandard */

$this->title = Yii::t('app', 'Create Vietgap Standard');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vietgap Standards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vietgap-standard-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
