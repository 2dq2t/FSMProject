<?php
$this->title = Yii::t('app','Backup / Restore data');
$this->params ['breadcrumbs'] [] = [
    'label' => Yii::t('app', 'Backup / Restore data')
];
?>

<?php
if(Yii::$app->session->getFlash('success')) {
    echo '<div class="alert alert-success">' . Yii::$app->session->getFlash('success') . "</div>\n";
}
if(Yii::$app->session->getFlash('error')) {
    echo '<div class="alert alert-error">' . Yii::$app->session->getFlash('error') . "</div>\n";
}
?>

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