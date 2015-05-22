<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 13/05/2015
 * Time: 4:35 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
include '_search.php';
?>
<div id="single-product">
    <div class="container">

        <div class="no-margin col-xs-12 col-sm-6 col-md-5 gallery-holder">
            <div class="product-item-holder size-big single-product-gallery small-gallery">
                <?php
                echo "<div id='owl-single-product'>";

                $count = 1;
                foreach ($productImage as $item) {

                    echo "<div class='single-product-gallery-item' id='slide" . $count . "'>";
                    echo "<a data-rel='prettyphoto' href='" . $item['path'] . "'>";
                    echo "<img class='img-responsive' alt='' src='mediacenter/assets/images/blank.gif'
                                 data-echo='" . $item['path'] . "'/>";
                    echo "</a>";
                    echo "</div>";
                    $count++;
                }

                echo "<!-- /.single-product-gallery-item -->";
                echo "</div>";
                echo "<!-- /.single-product-slider -->";


                echo "<div class='single-product-gallery-thumbs gallery-thumbs'>";

                echo "<div id='owl-single-product-thumbnails'>";
                $count = 0;
                foreach ($productImage as $item) {
                    echo "<a class='horizontal-thumb active' data-target='#owl-single-product' data-slide='" . $count . "'
                           href='#slide" . $count . "'>";
                    echo "<img width='67' alt='' src='mediacenter/assets/images/blank.gif'
                                 data-echo='" . $item['path'] . "'/>";
                    echo "</a>";
                    $count++;
                }
                echo "</div>";
                ?>
                <!-- /#owl-single-product-thumbnails -->

                <div class="nav-holder left hidden-xs">
                    <a class="prev-btn slider-prev" data-target="#owl-single-product-thumbnails" href="#prev"></a>
                </div>
                <!-- /.nav-holder -->

                <div class="nav-holder right hidden-xs">
                    <a class="next-btn slider-next" data-target="#owl-single-product-thumbnails" href="#next"></a>
                </div>
                <!-- /.nav-holder -->

            </div>
            <!-- /.gallery-thumbs -->

        </div>
        <!-- /.single-product-gallery -->
    </div>
    <!-- /.gallery-holder -->
    <div class="no-margin col-xs-12 col-sm-7 body-holder">
        <div class="body">
            <div class="star-holder inline">
                <div class="star" data-score="4"></div>
            </div>
            <div class="availability"><label>Availability:</label><span
                    class="available"><?php if ($productDetail[0]['quantity_in_stock'] - $productDetail[0]['sold'] > 0) echo $productDetail[0]['quantity_in_stock'] - $productDetail[0]['sold']; else echo "Trong kho"; ?></span>
            </div>

            <div class="title"><p><a href="#"><?php echo $productDetail[0]['name'] ?></a></p></div>
            <div class="text"><p><strong>Mã số sản phẩm:</strong> <?php echo $productDetail[0]['barcode'] ?></p></div>
            <div class="text"><p><strong class="inline">Tiêu chuẩn:</strong><span class="inline"
                                                                                  style="margin-left: 3px"><a
                            href="http://vietgap.gov.vn/Content.aspx?mode=uc&page=About&Option=7" target="_blank"
                            rel="nofollow">Chứng nhận rau an toàn VIETGAP</a></span></p></div>
            <div class="brand">Fresh Garden</div>

            <div class="social-row">
                <!--horizontal count-->
                <span class="st_fblike_hcount"></span>
                <span class="st_facebook_hcount"></span>

            </div>

            <div class="buttons-holder">

                <script>
                    function addWishList(id){
                        $.ajax({
                            url: '/FSMProject/frontend/web/index.php?r=site/wish-list',
                            type: 'POST',
                            dataType: 'json',
                            success: function(data) {
                                // process data
                                alert(data);
                            }
                        });
                    }
                </script>
                <a class="btn-add-to-wishlist" id="<?= $productDetail[0]['id']?>" onclick="addWishList(<?= $productDetail[0]['id']?>)" href="#">Thêm vào danh mục yêu thích</a>
            </div>

            <div class="excerpt">
                <p><?php echo $productDetail[0]['description'] ?></p>
            </div>

            <div class="prices">
                <div class="price-current"><?php echo $productDetail[0]['price'] ?> VND</div>
            </div>

            <div class="qnt-holder">
                <div class="le-quantity">
                    <form>
                        <a class="minus" href="#reduce"></a>
                        <input name="quantity" readonly="readonly" type="text" value="1"/>
                        <a class="plus" href="#add"></a>
                    </form>
                </div>
                <a id="addto-cart" href="cart.html" class="le-button huge">Thêm vào giỏ hàng</a>
            </div>
            <!-- /.qnt-holder -->
        </div>
        <!-- /.body -->

    </div>
    <!-- /.body-holder -->
</div>
<!-- /.container -->
</div><!-- /.single-product -->

<!-- ========================================= SINGLE PRODUCT TAB ========================================= -->
<section id="single-product-tab">
    <div class="container">
        <div class="tab-holder">

            <ul class="nav nav-tabs simple">
                <li class="active"><a href="#description" data-toggle="tab">Miêu tả</a></li>
                <li><a href="#additional-info" data-toggle="tab">Thông tin chung</a></li>
                <li><a href="#reviews" data-toggle="tab">Nhận xét</a></li>
            </ul>
            <!-- /.nav-tabs -->

            <div class="tab-content">
                <div class="tab-pane active" id="description">
                    <p><?php echo $productDetail[0]['description'] ?></p>

                    <div class="meta-row">
                        <div class="inline">
                            <label>tags:</label>
                            <span><a href="#">fast</a>,</span>
                            <span><a href="#">gaming</a>,</span>
                            <span><a href="#">strong</a></span>
                        </div>
                        <!-- /.inline -->
                    </div>
                    <!-- /.meta-row -->
                </div>
                <!-- /.tab-pane #description -->

                <div class="tab-pane" id="additional-info">
                    <ul class="tabled-data">
                        <li>
                            <label>weight</label>

                            <div class="value">7.25 kg</div>
                        </li>
                        <li>
                            <label>dimensions</label>

                            <div class="value">90x60x90 cm</div>
                        </li>
                        <li>
                            <label>size</label>

                            <div class="value">one size fits all</div>
                        </li>
                        <li>
                            <label>color</label>

                            <div class="value">white</div>
                        </li>
                        <li>
                            <label>guarantee</label>

                            <div class="value">5 years</div>
                        </li>
                    </ul>
                    <!-- /.tabled-data -->

                    <div class="meta-row">
                        <div class="inline">
                            <label>SKU:</label>
                            <span>54687621</span>
                        </div>
                        <!-- /.inline -->

                        <span class="seperator">/</span>

                        <div class="inline">
                            <label>categories:</label>
                            <span><a href="#">-50% sale</a>,</span>
                            <span><a href="#">gaming</a>,</span>
                            <span><a href="#">desktop PC</a></span>
                        </div>
                        <!-- /.inline -->

                        <span class="seperator">/</span>

                        <div class="inline">
                            <label>tag:</label>
                            <span><a href="#">fast</a>,</span>
                            <span><a href="#">gaming</a>,</span>
                            <span><a href="#">strong</a></span>
                        </div>
                        <!-- /.inline -->
                    </div>
                    <!-- /.meta-row -->
                </div>
                <!-- /.tab-pane #additional-info -->


                <div class="tab-pane" id="reviews">
                    <div class="fb-comments" data-href="<?php echo "localhost/" . Yii::$app->request->getUrl(); ?>"
                         data-numposts="5" data-colorscheme="light"></div>
                    <!-- /.comments -->
                </div>
                <!-- /.tab-pane #reviews -->
            </div>
            <!-- /.tab-content -->

        </div>
        <!-- /.tab-holder -->
    </div>
    <!-- /.container -->
</section><!-- /#single-product-tab -->
<!-- ========================================= SINGLE PRODUCT TAB : END ========================================= -->
<footer id="footer" class="color-bg">