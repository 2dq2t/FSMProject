<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
$this->title =$productDetail[0]['name'];
?>
<?php echo $this->render('_navbar',[
    'modelCategory' => $modelCategory,
]);
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li><a href="<?php echo $baseUrl.'index.php?r=site/view-detail&product='.$productDetail[0]['name']?>"><?php echo $productDetail[0]['name'] ?></a></li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php echo $this->render('_category',[
                'modelCategory' => $modelCategory,
            ]);
            ?>
            <?php echo $this->render('_leftBanner');
            ?>

            <?php echo $this->render('_specialProduct');
            ?>
            <?php echo $this->render('_bestSeller');
            ?>
        </column>
        <div id="content" class="productpage col-sm-9">      <div class="row">



                <div class="col-sm-6 product-left">
                    <div class="product-info">



                        <ul class="left product-image thumbnails">

                            <!-- Megnor Cloud-Zoom Image Effect Start -->
                            <?php
                                echo "<li class='image'><a class='thumbnail' href='".$productImage[0]['path']."' title='".$productDetail[0]['name']."'><img src='".$productImage[0]['path']."' title='".$productDetail[0]['name']."' alt='".$productDetail[0]['name']."' /></a></li>";
                            ?>

                            <div class="additional-carousel">
                                <div class="customNavigation">
                                    <span class="prev"></span>
                                    <span class="next"></span>
                                </div>

                                <div id="additional-carousel" class="image-additional product-carousel">
                                    <?php
                                    foreach ($productImage as $item) {

                                    echo "<div class='slider-item'>";
                                        echo "<div class='product-block'>";
                                        echo "<a href='" . $item['path'] . "' title='".$productDetail[0]['name']." ' class='thumbnail elevatezoom-gallery' data-image='" . $item['path'] . "' data-zoom-image='" . $item['path'] . "'><img src='" . $item['path'] . "' width='74' title='".$productDetail[0]['name']." ' alt='".$productDetail[0]['name']."' /></a>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                    ?>
                                </div>
                                <span class="additional_default_width" style="display:none; visibility:hidden"></span>
                            </div>


                            <!-- Megnor Cloud-Zoom Image Effect End-->
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 product-right">
                    <h3 class="product-title"><?php echo $productDetail[0]['name'] ?> </h3>
                    <ul class="list-unstyled">
                        <li><span>Mã số sản phẩm:</span><?php echo $productDetail[0]['barcode'] ?></li>
                        <li><span>Số lượng: </span><?php if ($productDetail[0]['quantity_in_stock'] - $productDetail[0]['sold'] > 0) echo $productDetail[0]['quantity_in_stock'] - $productDetail[0]['sold']; else echo "Trong kho"; ?></li>
                        <li><p><span>Tiêu chuẩn:</span><span class="inline" style="margin-left: 3px"><a  href="http://vietgap.gov.vn/Content.aspx?mode=uc&page=About&Option=7" target="_blank"
                                            rel="nofollow">Chứng nhận rau an toàn VIETGAP</a></span></p></li>
                        <li><p><?php echo $productDetail[0]['intro'] ?> </p></li>
                    </ul>


                    <ul class="list-unstyled price">
                        <li class="price-title">Giá:</li>
                        <li class="price-normal">
                            <h2 ><?php echo $productDetail[0]['price'] ?> VND</h2>
                        </li>
                        <li class="tax price-tax">Thuế:<span class="price-tax"><?php echo $productDetail[0]['tax'] ?> VND</span></li>
                        <br/>
                    </ul>
                    <div id="product">

                        <div class="form-group quntity">
                            <label class="control-label" for="input-quantity">SL:</label>
                            <input type="text" name="quantity" value="1" size="2" id="input-quantity" class="form-control" />
                            <input type="hidden" name="product_id" value="43" />


                            <button type="button" id="button-cart" data-loading-text="Loading..." title="Add to Cart" class="addtocart" ><span>Add to Cart</span></button> <span>&nbsp;&nbsp;- OR -&nbsp;&nbsp;</span>
                            <script>
                                function addWishList(product_id) {
                                    $.ajax({
                                        url: 'fsmproject/frontend/web/index.php?r=site/wish-list',
                                        type: 'post',
                                        data: {product_id: product_id},
                                        dataType: 'json',
                                        success: function(json) {
                                            $('.alert').remove();

                                            if (json['success']) {
                                                $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                            }

                                            if (json['info']) {
                                                $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                                            }

                                            $('#wishlist-total').html(json['total']);

                                            $('html, body').animate({ scrollTop: 0 }, 'slow');
                                        }
                                    });
                                }
                            </script>
                            <div class="btn-group">
                                <button type="button"  class="wishlist" title="Add to Wish List" onclick="addWishList(<?php echo $productDetail[0]['id'] ?> );">Add to Wish List</button>
                                <button type="button"  class="compare" title="Add to Compare" onclick="compare.add('43');">Add to Compare</button>
                            </div>
                        </div>
                    </div>



                    <div class="rating-wrapper">
                        <div id="star" class="star" data-score="4"></div>
                    </div>

                    <!-- Like Facebook Button -->
                    <div class="fb-like" data-href="<?php echo "localhost/".Yii::$app->request->getUrl(); ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
                    <!-- Like Facebook Button END -->

                </div>
                <div class="col-sm-12 product-description">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-description" data-toggle="tab">Miêu tả</a></li>
                        <li><a href="#tab-review" data-toggle="tab">Nhận xét</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-description"><p><?php echo $productDetail[0]['description'] ?></p></div>

                        <div class="tab-pane" id="tab-review">
                            <div class="fb-comments" data-href="<?php echo "localhost/".Yii::$app->request->getUrl(); ?>"
                                 data-numposts="5" data-colorscheme="light"></div>
                            <!-- /.comments -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">

                <div class="box-heading">Related Products</div>
                <div class="box-content">
                    <div id="products-related" class="related-products">

                        <div class="customNavigation">
                            <a class="prev">&nbsp;</a>
                            <a class="next">&nbsp;</a>
                        </div>


                        <div class="box-product product-carousel" id="related-carousel">

                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=28"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/15-220x200.jpg" alt="Arcu vitae imperdiet " title="Arcu vitae imperdiet " class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=28" title="Arcu vitae imperdiet ">
                                                        Arcu vitae imperdiet..				</a>
                                                </h4>

                                                <div class="price">
                                                    $122.00																		<span class="price-tax">Ex Tax: $100.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('28');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/12-220x200.jpg" alt="Aliquam volutpat" title="Aliquam volutpat" class="img-responsive" />
                                                <div class="saleback">
                                                    <span class="saleicon sale">Sale</span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30" title="Aliquam volutpat">
                                                        Aliquam volutpat				</a>
                                                </h4>

                                                <div class="price">
                                                    <span class="price-old">$122.00</span><span class="price-new">$98.00</span>
                                                    <span class="price-tax">Ex Tax: $80.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('30');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=31"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/3-220x200.jpg" alt="Praesent fringilla" title="Praesent fringilla" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=31" title="Praesent fringilla">
                                                        Praesent fringilla				</a>
                                                </h4>

                                                <div class="price">
                                                    $98.00																		<span class="price-tax">Ex Tax: $80.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('31');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=32"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/22-220x200.jpg" alt="Nascetur ridiculus mus" title="Nascetur ridiculus mus" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=32" title="Nascetur ridiculus mus">
                                                        Nascetur ridiculus m..				</a>
                                                </h4>

                                                <div class="price">
                                                    $122.00																		<span class="price-tax">Ex Tax: $100.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('32');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=33"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/9-220x200.jpg" alt="Vactramn denim" title="Vactramn denim" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=33" title="Vactramn denim">
                                                        Vactramn denim				</a>
                                                </h4>

                                                <div class="price">
                                                    $242.00																		<span class="price-tax">Ex Tax: $200.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('33');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=35"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/27-220x200.jpg" alt="Praesent semneck" title="Praesent semneck" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=35" title="Praesent semneck">
                                                        Praesent semneck				</a>
                                                </h4>

                                                <div class="price">
                                                    $122.00																		<span class="price-tax">Ex Tax: $100.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('35');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/18-220x200.jpg" alt="Cum sociis natoqu" title="Cum sociis natoqu" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=40" title="Cum sociis natoqu">
                                                        Cum sociis natoqu				</a>
                                                </h4>

                                                <div class="price">
                                                    $123.20																		<span class="price-tax">Ex Tax: $101.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('40');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=41"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/25-220x200.jpg" alt="Consectetur adipiscing" title="Consectetur adipiscing" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=41" title="Consectetur adipiscing">
                                                        Consectetur adipisci..				</a>
                                                </h4>

                                                <div class="price">
                                                    $122.00																		<span class="price-tax">Ex Tax: $100.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('41');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=44"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/24-220x200.jpg" alt="Palm Treo Pro" title="Palm Treo Pro" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=44" title="Palm Treo Pro">
                                                        Palm Treo Pro				</a>
                                                </h4>

                                                <div class="price">
                                                    $1,202.00																		<span class="price-tax">Ex Tax: $1,000.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('44');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=45"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/26-220x200.jpg" alt="Pellentesque augue" title="Pellentesque augue" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=45" title="Pellentesque augue">
                                                        Pellentesque augue				</a>
                                                </h4>

                                                <div class="price">
                                                    $2,000.00																		<span class="price-tax">Ex Tax: $2,000.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('45');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-item">
                                <div class="product-block product-thumb transition">
                                    <div class="product-block-inner">
                                        <div class="image">
                                            <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=47"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/13-220x200.jpg" alt="Aliquam suscipit" title="Aliquam suscipit" class="img-responsive" />

                                            </a>
                                        </div>
                                        <div class="product-details">
                                            <div class="caption">
                                                <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=47" title="Aliquam suscipit">
                                                        Aliquam suscipit				</a>
                                                </h4>

                                                <div class="price">
                                                    $122.00																		<span class="price-tax">Ex Tax: $100.00</span>
                                                </div>
                                            </div>
                                            <div class="button-group">
                                                <button type="button"  title="Add to Cart" class="addtocart" onclick="cart.add('47');"> <span>Add to Cart</span></button>

                                            </div>
                                        </div>
                                        <span class="related_default_width" style="display:none; visibility:hidden"></span>
                                        <!-- Megnor Related Products Start -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="product-tag"><b>Tags:</b>
                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;tag=Donec">Donec</a>,
                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;tag=Maecenas">Maecenas</a>,
                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;tag=Nulla">Nulla</a>,
                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;tag=Lorem">Lorem</a>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript"><!--
    $('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
        $.ajax({
            url: 'index.php?route=product/product/getRecurringDescription',
            type: 'post',
            data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
            dataType: 'json',
            beforeSend: function() {
                $('#recurring-description').html('');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['success']) {
                    $('#recurring-description').html(json['success']);
                }
            }
        });
    });
    //--></script>
<script type="text/javascript"><!--
    $('#button-cart').on('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function() {
                $('#button-cart').button('loading');
            },
            complete: function() {
                $('#button-cart').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                element.parent().before('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                element.before('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['recurring']) {
                        $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('has-error');
                }

                if (json['success']) {
                    $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    $('#cart-total').html(json['total']);

                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            }
        });
    });
    //--></script>

<script type="text/javascript"><!--
    $(document).ready(function() {
        $('.thumbnails').magnificPopup({
            type:'image',
            delegate: 'a',
            gallery: {
                enabled:true
            }
        });
        $('#star').raty({
            score: function() {
                return $(this).attr('data-score');
            }
        });
    });

    //--></script>

