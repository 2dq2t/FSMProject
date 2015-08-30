<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 24/08/2015
 * Time: 8:38 CH
 */$this->title = Yii::t('app','VietGap Standard');
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/vietgap-standard" ?>"><?= Yii::t('app','VietGap Standard') ?></a>
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
            <?php if(empty($pagination)):?>
                <div> <h1><?=$regulation_policy['title']?></h1></div>
                <div><?=$regulation_policy['full_post']?> </div>
            <?php endif?>
            <?php if(!empty($pagination)):?>
            <?php foreach($vietgap_standard as $item):?>
            <div class="aboutus" ><h1> <a href="<?= "index.php?r=site/vietgap-standard&vs=".$item['alias']?>"><?= $item['title']?></a></h1>
                <div class="image1" ><img src="<?=$item['image']?>" class="image1">&nbsp;</div>
                <div class="content post-content"><?= $item['post_info']?></div>
            </div>
        </div>
        <?php endforeach?>
        <?php if($pagination->totalCount >9):?>
            <div class="pagination-wrapper">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]);
                ?>
            </div>
        <?php endif?>
        <?php endif?>
        </div>
    </div>
</div>