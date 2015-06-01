<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\alert\Alert;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Permissions');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo Alert::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : Alert::TYPE_DANGER,
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
            'delay' => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
            'showSeparator' => true,
            'options' => ['format' => 'raw']
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="auth-item-index">

    <?= \yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => '<i class="fa fa-edit"></i> ' . Yii::t('app', 'Permissions'),
                'content' => $this->render('_gridview', ['data' => $permissions, 'id' => 'operations', 'label' => Yii::t('app', 'Permissions'), 'type' => \yii\rbac\Item::TYPE_PERMISSION]),
                'active' => true,
            ],
            [
                'label' => '<i class="fa fa-users"></i> ' . Yii::t('app', 'Roles'),
                'content' => $this->render('_gridview', ['data' => $roles,'id' => 'roles', 'label' => 'Roles', 'type' => \yii\rbac\Item::TYPE_ROLE]),
            ],
        ],
        'encodeLabels'=>false
    ]); ?>

</div>
