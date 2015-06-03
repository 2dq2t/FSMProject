<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 27/05/2015
 * Time: 3:09 CH
 */
?>
<?php echo $this->render('_navbar',[
    'categories' => $categories,
]);
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="<?=Yii::$app->request->baseUrl?>"><i class="fa fa-home"></i></a></li>
        <li><a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$category_name?>"><?=$category_name?></a></li>
    </ul>
<div class="row content-subinner">
    <column id="column-left" class="col-sm-3 hidden-xs">
        <?php echo $this->render('_category',[
            'categories' => $categories,
        ]);
        ?>
        <?php echo $this->render('_leftBanner');
        ?>

        <?php echo $this->render('_specialProduct');
        ?>
        <?php echo $this->render('_bestSeller');
        ?>
    </column>
    <div id="content" class="col-sm-9 categorypage">
        <h2 class="page-title"><?=$category_name?></h2>
        <div class="row category_thumb">
            <div class="col-sm-2 category_img"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/inner_banner-720x112.jpg" alt="Electronics" title="Electronics" class="img-thumbnail" /></div>
            <div class="col-sm-10 category_description">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</div>
        </div>

        <div class="category_filter">
            <div class="col-md-4 btn-list-grid">
                <div class="btn-group">
                    <button type="button" id="list-view" class="btn btn-default list" data-toggle="tooltip" title="List"><i class="fa fa-th-list"></i></button>
                    <button type="button" id="grid-view" class="btn btn-default grid" data-toggle="tooltip" title="Grid"><i class="fa fa-th"></i></button>
                </div>
            </div>
            <div class="compare-total"><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/compare" id="compare-total">Product Compare (0)</a></div>
            <div class="pagination-right">
                <div class="sort-by-wrapper">
                    <div class="col-md-2 text-right sort-by">
                        <label class="control-label" for="input-sort">Sắp xếp theo:</label>
                    </div>
                    <div class="col-md-3 text-right sort">
                        <select id="input-sort" class="form-control" onchange="location = this.value;">
                            <option value="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$category_name."&sort=default&order=ASC"?>" selected="selected">Mặc định</option>
                            <option value="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$category_name."&sort=name&order=ASC"?>">Tên (A - Z)</option>
                            <option value="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$category_name."&sort=name&order=DESC"?>">Tên (Z - A)</option>
                            <option value="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$category_name."&sort=price&order=ASC"?>">Giá (Thấp &gt; Cao)</option>
                            <option value="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$category_name."&sort=price&order=DESC"?>">Giá (Cao &gt; Thấp)</option>
                            <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20&amp;sort=rating&amp;order=DESC">Rating (Highest)</option>
                            <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20&amp;sort=rating&amp;order=ASC">Rating (Lowest)</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <div class="row productlist-grid">
            <?php foreach($product as $item){?>
            <div class="product-layout product-list col-xs-12">
                <div class="product-thumb product-block">
                    <div class="product-block-inner">
                        <div class="image">
                            <a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/view-detail&product=".$item['product_name']?>"><img src="<?= $item['image_path']?>" alt="<?=$item['product_name']?>" title="<?=$item['product_name']?>" class="img-responsive" /></a>

                        </div>
                        <div class="product-details">
                            <div class="caption">
                                <div class="left">

                                    <h4><a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/view-detail&product=".$item['product_name']?>" title="<?=$item['product_name']?>">
                                            <?=$item['product_name']?>				</a>
                                    </h4>


                                    <div class="desc"><?= $item['product_intro']?></div>



                                    <div class="compare-wishlist">
                                        <div class="wishlist-btn">
                                            <button type="button" class="wishlist"  title="Add to Wish List" onclick="wishlist.add(<?php echo $item['product_id']; ?> );">Thêm vào danh mục yêu thích</button>
                                        </div>
                                        <div class="compare-btn">
                                            <button type="button" class="compare"  title="Add to Compare" onclick="compare.add('31');">Add to Compare</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="right">

                                    <div class="price">
                                        <?= $item['product_price']." VND"?>                                                      <span class="price-tax">Thuế: <?= $item['product_tax']." VND"?> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="button-group">
                                <button type="button"   title="Add to Cart" class="addtocart" onclick="cart.add('31');"><span>Add to Cart</span></button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="pagination-wrapper">
            <div class="col-sm-6 text-left page-link"><ul class="pagination"><li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20&amp;page=1">|&lt;</a></li><li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20&amp;page=1">&lt;</a></li><li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/category&amp;path=20&amp;page=1">1</a></li><li class="active"><span>2</span></li></ul></div>
            <div class="col-sm-6 text-right page-result">Showing 13 to 18 of 18 (2 Pages)</div>
        </div>
    </div>
</div>
</div>
