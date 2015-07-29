<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 27/05/2015
 * Time: 3:09 CH
 */
?>
<?php require('_header.php');
?>
<div class="container content-inner">
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php
            require('_category.php');
            echo $this->render('_leftBanner');
            require('_specialProduct.php');
            require('_bestSeller.php');
            ?>
        </column>
        <div id="content" class="col-sm-9">
            <ul class="breadcrumb">
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i
                            class="fa fa-home"></i></a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;search=lu">Search</a>
                </li>
            </ul>
            <h1 class="page-title">Search - <?php echo $q;?></h1>
            <?php \yii\widgets\ActiveForm::begin()?>
            <form >
            <fieldset>
                <label class="control-label " for="input-search"><b>Lựa chọn tìm kiếm</b></label>

                <div class="row">
                    <div class="col-sm-4">
                        <input type="text" name="search-key" value="<?php echo $q;?>" placeholder="Keywords" id="input-search"
                               class="form-control">
                    </div>
                    <div class="col-sm-3 sortcat">
                        <select id="search-option" name="search-option" class="form-control hasCustomSelect" style="-webkit-appearance: menulist-button; width: 226px;  height: 30px; font-size: 14px;">
                            <option value="all">Tất cả</option>
                            <option value="name">Tên sản phẩm</option>
                            <option value="category">Danh mục sản phẩm</option>
                        </select>
                    </div>

                    <div class="col-sm-3 subcategory">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="description" value="1" <?= $search_with_description?> id="description">
                            Tìm kiếm với phần miêu tả</label>
                    </div>
                </div>
            </fieldset>

            <input type="submit" value="Tìm kiếm" id="button-search" class="btn btn-primary">
            </form>
            <?php \yii\widgets\ActiveForm::end()?>
            <h2>Kết quả tìm kiếm</h2>


            <div class="category_filter">
                <div class="col-md-4 btn-list-grid">
                    <div class="btn-group">
                        <button type="button" id="list-view" class="btn btn-default list" data-toggle="tooltip" title=""
                                data-original-title="List"><i class="fa fa-th-list"></i></button>
                        <button type="button" id="grid-view" class="btn btn-default grid active" data-toggle="tooltip"
                                title="" data-original-title="Grid"><i class="fa fa-th"></i></button>
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
                                    value="<?= Yii::$app->request->getUrl() ?>"
                                    selected="selected"><?= Yii::t('app', 'DefaultLabel') ?></option>
                                <option
                                    value="<?= Yii::$app->request->getUrl() . "&sort=name&order=ASC" ?>"><?= Yii::t('app', 'NameAtoZLabel') ?></option>
                                <option
                                    value="<?= Yii::$app->request->getUrl() . "&sort=name&order=DESC" ?>"><?= Yii::t('app', 'NameZtoALabel') ?></option>
                                <option
                                    value="<?= Yii::$app->request->getUrl() . "&sort=price&order=ASC" ?>"><?= Yii::t('app', 'PriceLowToHighLabel') ?></option>
                                <option
                                    value="<?= Yii::$app->request->getUrl() . "&sort=price&order=DESC" ?>"><?= Yii::t('app', 'PriceHighToLowLabel') ?></option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row productlist-grid">
                <?php foreach ($products as $product) { ?>
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
                                    ?>
                                </div>
                                <div class="product-details">
                                    <div class="caption">
                                        <div class="left">

                                            <h4>
                                                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"
                                                   title="<?= ucwords($product['product_name']) ?>">
                                                    <?= ucwords($product['product_name']) ?>                </a>
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
                                                if (isset($product['product_offer'])) {
                                                    $new_price = $product['product_price'] * (100 / $product['product_offer']);
                                                    echo number_format($new_price) . " " . Yii::t('app', 'VNDLabel');
                                                } else
                                                    echo number_format($product['product_price']) . " " . Yii::t('app', 'VNDLabel') ?>
                                                <span
                                                    class="price-tax"><?= Yii::t('app', 'TaxLabel') . " " . number_format($product['product_tax']) . " " . Yii::t('app', 'VNDLabel') ?> </span>
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
