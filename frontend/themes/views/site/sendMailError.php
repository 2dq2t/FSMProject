<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 24/08/2015
 * Time: 8:38 CH
 */$this->title = Yii::t('app','Recipes');
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home"></i></a></li>
        <li>
            <a><?= Yii::t('app','Recipes') ?></a>
        </li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php
            echo $this->render('/layouts/_category.php');
            echo $this->render('/layouts/_leftBanner');
            echo $this->render('/layouts/_specialProduct.php');
            echo $this->render('/layouts/_bestSeller.php');
            ?>
        </column>
        <div id="content" class="col-sm-9 information-information">
                <div> <h1><?= Yii::t('app','ProxyError')?></h1></div>
                <div><?=Yii::t('app','ProxyErrorContent')?> </div>
        </div>
    </div>
</div>