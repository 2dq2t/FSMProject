<?php
$this->title = Yii::t('app','Backup / Restore data');
$this->params ['breadcrumbs'] [] = [
    'label' => Yii::t('app', 'Backup / Restore data')
];
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php if($message) { ?>
        <?php
        echo lavrentiev\yii2toastr\Toastr::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'success',
            'title' => (!empty($message['title'])) ? \yii\helpers\Html::encode($message['title']) : 'Title Not Set!',
            'message' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
            'clear' => false,
            'options' => [
                "closeButton" => true,
                "positionClass" => "toast-top-right",
                "timeOut" => (!empty($message['duration'])) ? \yii\helpers\Html::encode($message['duration']) : 0,
            ]
        ]);
    }
    ?>
<?php endforeach; ?>

<div class="backup-index">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render ( '_list',  [
                'dataProvider' => $dataProvider
            ]);
            ?>
        </div>
    </div>

</div>