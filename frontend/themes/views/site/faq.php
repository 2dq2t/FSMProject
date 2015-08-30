<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 24/08/2015
 * Time: 8:40 CH
 */$this->title = Yii::t('app','FAQs');
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/faq" ?>"><?= Yii::t('app','FAQs') ?></a>
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
            <?php foreach($faq as $item):?>
                <div class="aboutus" ><h1><?= $item['question']?></h1>
                    <div class="image1" >&nbsp;</div>
                    <div class="content "><?= $item['answer']?></div>
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
        </div>
    </div>
</div>