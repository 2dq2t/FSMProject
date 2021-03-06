<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 26/05/2015
 * Time: 1:12 SA
 */
?>
<div class="box">
    <div class="box-heading"><?= Yii::t('app', 'BestsellerLabel') ?></div>
    <div class="box-content">


        <div class="box-product product-grid" id="bestseller-grid">
            <?php
            $best_seller = Yii::$app->ProductBestSeller->getProductBestSeller();
            $count = 0;
            foreach ($best_seller as $product) {
                $count++;
                if ($count > 3)
                    break;
                ?>
                <div class="slider-item">
                    <div class="product-block product-thumb transition">
                        <div class="product-block-inner ">
                            <div class="image">
                                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=product/view-detail&product=" . $product['product_name'] ?>"><img
                                        src="<?= $product['product_image'] ?>"
                                        alt="<?= $product['product_name'] ?>" title="<?= $product['product_name'] ?>"
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
                                    <button type="button" title="<?= Yii::t('app', 'AddToCartLabel') ?>"
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
<span class="bestseller_default_width" style="display:none; visibility:hidden"></span>