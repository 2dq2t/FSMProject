<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RegulationPolicy */

$this->title = Yii::t('app', 'Create Regulation Policy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Regulation Policies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regulation-policy-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
