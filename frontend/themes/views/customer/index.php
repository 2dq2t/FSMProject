<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Customer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'password',
            'avatar',
            'dob',
            // 'gender',
            // 'auth_key',
            // 'password_reset_token',
            // 'created_at',
            // 'updated_at',
            // 'status',
            // 'address_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
