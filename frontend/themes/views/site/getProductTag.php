<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 27/05/2015
 * Time: 3:09 CH
 */
$this->title = Yii::t('app', 'ProductTagTitle');
?>
<?php require('_header.php');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/product-tag&tag=" . $_GET['tag'] ?>">Tag: <?php $tag = ''; if(isset($_GET['tag'])){ $tag =  $_GET['tag'];echo $tag;} ?> ?></a>
        </li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php
            require('_category.php');
            echo $this->render('_leftBanner');
            require('_specialProduct.php');
            require('_bestSeller.php');
            ?>
        </column>
        <div id="content" class="col-sm-9 categorypage">


            <div class="row category_thumb">
                <div class="col-sm-2 category_img"><img
                        src="images/data/inner-banner.jpg"
                        alt="Electronics" title="Electronics" class="img-thumbnail"/></div>
            </div>
            <h2 class="page-title">Tag: <?= $tag ?></h2>
            <div class="category_filter">
                <div class="col-md-4 btn-list-grid">
                    <div class="btn-group">
                        <button type="button" id="list-view" class="btn btn-default list" data-toggle="tooltip"
                                title="List"><i class="fa fa-th-list"></i></button>
                        <button type="button" id="grid-view" class="btn btn-default grid" data-toggle="tooltip"
                                title="Grid"><i class="fa fa-th"></i></button>
                    </div>
                </div>
                <div class="pagination-right">
                    <div class="sort-by-wrapper">
                        <div class="col-md-2 text-right sort-by">
                            <label class="control-label" for="input-sort"><?= Yii::t('app', 'SortByLabel') ?></label>
                        </div>
                        <div class="col-md-3 text-right sort">
                            <select id="input-sort" class="form-control" onchange="location = this.value;">
                                <option
                                    <?php if(empty($_GET['sort'])) echo "selected = 'true'"; ?>
                                    value="<?=Yii::$app->request->baseUrl."/index.php?r=site/get-product-category&tag=".$tag?>"><?= Yii::t('app', 'DefaultLabel') ?>
                                </option>
                                <option <?php if(isset($_GET['sort']) && isset($_GET['order'])){ if($_GET['sort'] == 'name' && $_GET['order'] == 'ASC') echo "selected = 'true'";}?>
                                    value="<?= Yii::$app->request->baseUrl."/index.php?r=site/get-product-category&tag=".$tag. "&sort=name&order=ASC" ?>"><?= Yii::t('app', 'NameAtoZLabel') ?></option>
                                <option <?php if(isset($_GET['sort']) && isset($_GET['order'])){ if($_GET['sort'] == 'name' && $_GET['order'] == 'DESC') echo "selected = 'true'";}?>
                                    value="<?= Yii::$app->request->baseUrl."/index.php?r=site/get-product-category&tag=".$tag. "&sort=name&order=DESC" ?>"><?= Yii::t('app', 'NameZtoALabel') ?></option>
                                <option <?php if(isset($_GET['sort']) && isset($_GET['order'])){ if($_GET['sort'] == 'price' && $_GET['order'] == 'ASC') echo "selected = 'true'";}?>
                                    value="<?= Yii::$app->request->baseUrl."/index.php?r=site/get-product-category&tag=".$tag. "&sort=price&order=ASC" ?>"><?= Yii::t('app', 'PriceLowToHighLabel') ?></option>
                                <option <?php if(isset($_GET['sort']) && isset($_GET['order'])){ if($_GET['sort'] == 'price' && $_GET['order'] == 'DESC') echo "selected = 'true'";}?>
                                    value="<?= Yii::$app->request->baseUrl."/index.php?r=site/get-product-category&tag=".$tag . "&sort=price&order=DESC" ?>"><?= Yii::t('app', 'PriceHighToLowLabel') ?></option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row productlist-grid">
                <?php foreach ($product_tag as $product) { ?>
                    <div class="product-layout product-list col-xs-12">
                        <div class="product-thumb product-block">
                            <div class="product-block-inner">
                                <div class="image">
                                    <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><img
                                            src="<?= $product['image_path'] ?>" alt="<?= $product['product_name'] ?>"
                                            title="<?= $product['product_name'] ?>" class="img-responsive"/></a>
                                    <?php
                                    if (isset($product['product_offer']) && $product['product_offer'] > 0)
                                        echo "<span class='saleicon sale'>" . Yii::t('app', 'SaleLabel') . "</span>";
                                    if (isset($product['product_rating']) && $product['product_rating'] > 0) {
                                        echo "<div class='rating' >";
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $product['product_rating']) {
                                                echo "<span class='fa fa-stack' ><i class='fa fa-star fa-stack-2x'' ></i ></span >";
                                            } else
                                                echo "<span class='fa fa-stack' ><i class='fa fa-star off fa-stack-2x' ></i ></span >";
                                        }
                                        echo "</div >";
                                    }
                                    ?>
                                </div>
                                <div class="product-details">
                                    <div class="caption">
                                        <div class="left">

                                            <h4>
                                                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"
                                                   title="<?=ucwords($product['product_name']) ?>">
                                                    <?= ucwords($product['product_name']) ?>
                                                </a>
                                            </h4>
                                            <div class="desc"><?= $product['product_intro'] ?></div>
                                            <div class="compare-wishlist">
                                                <div class="wishlist-btn">
                                                    <button type="button" class="wishlist" title="Add to Wish List"
                                                            onclick="wishlist.add(<?php echo $product['product_id']; ?> );"><?= Yii::t('app', 'AddToWishListLabel') ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">

                                            <div class="price">
                                                <?php
                                                if (!empty($product['product_offer'])) {
                                                    echo "<span class='price-old'>" . $product['product_price'] . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                    $new_price = Yii::$app->CommonFunction->getProductPrice($product['product_price'], $product['product_offer']);
                                                    echo "<span class='price-new'>" . $new_price . Yii::t('app', 'VNDLabel') . "</span>";
                                                } else
                                                    echo number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-group">
                                        <button type="button" title="<?= Yii::t('app', 'AddToCartLabel') ?>"
                                                class="addtocart" onclick="cart.add('31');">
                                            <span><?= Yii::t('app', 'AddToCartLabel') ?></span></button>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="pagination-wrapper">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pagination,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
