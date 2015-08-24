<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
$this->title = Yii::t('app', 'WishListLabel');
echo $this->render('/layouts/_header');
?>
<div class="container content-inner">

    <div class="row content-subinner" style="padding-top: 5px;">
        <div class="container">

            <div class="content_bottom">

                <column id="column-left" class="col-sm-3 hidden-xs">
                    <?php
                    echo $this->render('/layouts/_category.php');
                    echo $this->render('/layouts/_leftBanner');
                    echo $this->render('/layouts/_specialProduct.php');
                    echo $this->render('/layouts/_bestSeller.php');
                    ?>
                </column>
                <div id="content" class="col-sm-9">
                    <ul class="breadcrumb">
                        <li><a href="<?= Yii::$app->request->baseUrl ?>"><i class="fa fa-home"></i></a></li>
                        <li>
                            <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=wish-list/get-wish-list" ?>"><?= Yii::t('app', 'WishListLabel') ?> </a>
                        </li>
                    </ul>
                    <h1 id="wish_list"
                        class="page-title"><?= Yii::t('app', 'WishListLabel') ?><?php if (!Yii::$app->user->isGuest) {
                            $number_product = Yii::$app->CommonFunction->getNumberProductWishList(Yii::$app->user->identity->getId());
                            echo " (" . $number_product . ")";
                        } ?>
                    </h1>
                    <?php if (empty($wish_list_product)) { ?>
                        <p><?= Yii::t('app', 'WishListNotice01') ?></p>
                        <div class="buttons">
                            <div class="pull-right"><a
                                    href="<?= $baseUrl ?>"
                                    class="btn btn-primary"><?= Yii::t('app', 'HomeLabel') ?></a></div>
                        </div>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered shopping-cart">
                                <thead>
                                <tr>
                                    <td class="text-center col-sm-2"><?= Yii::t('app', 'ImageLabel') ?></td>
                                    <td class="text-center"><?= Yii::t('app', 'ProductNameLabel') ?></td>
                                    <td class="text-center"><?= Yii::t('app', 'WeightLabel') ?></td>
                                    <td class="text-right"><?= Yii::t('app', 'ProductPrice') ?></td>
                                    <td class="text-right"><?= Yii::t('app', 'ActionLabel') ?></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($wish_list_product as $product) { ?>
                                <tr id="<?= "product" . $product['product_id'] ?>">
                                    <td class="text-center  col-sm-2 row-sm-2">
                                        <a href="<?= $baseUrl . "/index.php?r=product/view-detail&product=" . $product['product_name'] ?>"><img
                                                style="width: 94px;height: 94px"
                                                src="<?= $product['product_image'] ?>"
                                                alt="<?= $product['product_name'] ?>"
                                                title="<?= $product['product_name'] ?>" class="img-thumbnail"/></a>
                                    </td>
                                    <td class="text-center"><a
                                            href="<?= $baseUrl . "/index.php?r=product/view-detail&product=" . $product['product_name'] ?>"><?= ucwords($product['product_name']) ?></a>
                                        <br/>
                                        <small><?= Yii::t('app', 'QuantityLabel') ?>
                                            : <?php if ($product['product_quantity'] - $product['product_sold'] > 0) echo $product['product_quantity'] - $product['product_sold'];
                                            else echo Yii::t('app', 'ProductStatus') ?>
                                        </small>
                                    </td>
                                    <td class="text-center"><?= $product['product_unit'] ?></td>
                                    <td class="text-right"><?php
                                        if (!empty($product['product_offer'])) {
                                            $new_price = Yii::$app->CommonFunction->getProductPrice($product['product_price'], $product['product_offer']);
                                            echo number_format($new_price) . " " . Yii::t('app', 'VNDLabel');
                                        } else
                                            echo number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel');
                                        ?></td>
                                    <td class="text-right">
                                        <button id="" type="button"
                                                data-toggle="tooltip" title="<?= Yii::t('app', 'AddToCartLabel') ?>"
                                                class="btn btn-primary"
                                                onclick="cart.add('<?= $product['product_id'] ?>');"><i
                                                class="fa fa-shopping-cart"></i></button>
                                        <button id="<?= "remove" . $product['product_id'] ?>" type="button"
                                                data-toggle="tooltip"
                                                title="<?= Yii::t('app', 'DeleteWishLishLabel') ?>"
                                                class="btn btn-danger"
                                                onclick="wishlist.remove(<?= $product['product_id'] ?>);"><i
                                                class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    <?php } ?>

                    <div class="box">
                        <div class="box-heading"><?= Yii::t('app', 'RecentlyProductLabel') ?></div>
                        <div class="box-content">
                            <div id="products-related" class="related-products">

                                <div class="customNavigation">
                                    <a class="prev">&nbsp;</a>
                                    <a class="next">&nbsp;</a>
                                </div>


                                <div class="box-product product-carousel " id="featured-carousel">

                                    <?php if (count($product_session) > 0) foreach ($product_session as $product) { ?>
                                        <div class="slider-item">
                                            <div class="product-block product-thumb transition">
                                                <div class="product-block-inner ">
                                                    <div class="image">
                                                        <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=product/view-detail&product=" . $product['product_name'] ?>"><img
                                                                src="<?= $product['product_image'] ?>"
                                                                alt="<?= $product['product_name'] ?>"
                                                                title="<?= $product['product_name'] ?>"
                                                                class="img-responsive"/></a>
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
                                                            <h4>
                                                                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=product/view-detail&product=" . $product['product_name'] ?>"
                                                                   title="<?= ucwords($product['product_name']) ?>">
                                                                    <?= ucwords($product['product_name']) ?></a>
                                                            </h4>

                                                            <div class="price">
                                                                <?php
                                                                if (!empty($product['product_offer'])) {
                                                                    echo "<span class='price-old'>" . number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                    $new_price = Yii::$app->CommonFunction->getProductPrice($product['product_price'], $product['product_offer']);
                                                                    echo "<span class='price-new'>" . number_format($new_price) . " " . Yii::t('app', 'VNDLabel') . "</span>";
                                                                } else {
                                                                    echo number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel');
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="button-group">
                                                            <button type="button"
                                                                    title="<?= Yii::t('app', 'AddToCartLabel') ?>"
                                                                    class="addtocart"
                                                                    onclick="cart.add('<?= $product['product_id'] ?>');">
                                                                <span><?= Yii::t('app', 'AddToCartLabel') ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $(document).ready(function () {
        $('.thumbnails').magnificPopup({
            type: 'image',
            delegate: 'a',
            gallery: {
                enabled: true
            }
        });
    });

    //-->
</script>




