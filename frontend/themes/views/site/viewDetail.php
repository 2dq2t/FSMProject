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
                            <li class="image"><a class="thumbnail" href="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-500x500.jpg" title="Nullam molli dolor "><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-320x340.jpg" title="Nullam molli dolor " alt="Nullam molli dolor " /></a></li>


                            <div class="additional-carousel">
                                <div class="customNavigation">
                                    <span class="prev"></span>
                                    <span class="next"></span>
                                </div>

                                <div id="additional-carousel" class="image-additional product-carousel">

                                    <!--<div class="slider-item">
                                        <div class="product-block">
                                            <li>
                                                <a href="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-500x500.jpg" title="Nullam molli dolor " class="thumbnail" data-image="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-320x340.jpg"><img  src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/23-320x340.jpg" width="74" title="Nullam molli dolor " alt="Nullam molli dolor " /></a>

                                            </li>
                                        </div>
                                    </div>-->
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
                        <li><p><span>Tiêu chuẩn:</span><span class="inline"
                                                                                      style="margin-left: 3px"><a
                                            href="http://vietgap.gov.vn/Content.aspx?mode=uc&page=About&Option=7" target="_blank"
                                            rel="nofollow">Chứng nhận rau an toàn VIETGAP</a></span></p>
                    </ul>


                    <ul class="list-unstyled price">
                        <li class="price-title">Giá:</li>
                        <li class="price-normal">
                            <h2 ><?php echo $productDetail[0]['price'] ?> VND</h2>
                        </li>
                        <li class="tax price-tax">Ex Tax:<span class="price-tax">$500.00</span></li>
                        <br/>
                    </ul>
                    <div id="product">

                        <div class="form-group quntity">
                            <label class="control-label" for="input-quantity">SL:</label>
                            <input type="text" name="quantity" value="1" size="2" id="input-quantity" class="form-control" />
                            <input type="hidden" name="product_id" value="43" />


                            <button type="button" id="button-cart" data-loading-text="Loading..." title="Add to Cart" class="addtocart" ><span>Add to Cart</span></button> <span>&nbsp;&nbsp;- OR -&nbsp;&nbsp;</span>

                            <div class="btn-group">
                                <button type="button"  class="wishlist" title="Add to Wish List" onclick="wishlist.add('43');">Add to Wish List</button>
                                <button type="button"  class="compare" title="Add to Compare" onclick="compare.add('43');">Add to Compare</button>
                            </div>
                        </div>
                    </div>



                    <div class="rating-wrapper">
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star off fa-stack-1x"></i></span>
                        <span class="fa fa-stack"><i class="fa fa-star off fa-stack-1x"></i></span>
                        <a class="review-count" href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">1 reviews</a><a class="write-review" href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><i class="fa fa-pencil"></i> Write a review</a>
                    </div>

                    <!-- Like Facebook Button -->
                    <div class="addthis_toolbox addthis_default_style fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
                    <!-- Like Facebook Button END -->

                </div>
                <div class="col-sm-12 product-description">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-description" data-toggle="tab">Description</a></li>
                        <li><a href="#tab-specification" data-toggle="tab">Specification</a></li>
                        <li><a href="#tab-review" data-toggle="tab">Reviews (1)</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-description"><p><?php echo $productDetail[0]['description'] ?></p></div>
                        <div class="tab-pane" id="tab-specification">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td colspan="2"><strong>Memory</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>test 1</td>
                                    <td>8gb</td>
                                </tr>
                                </tbody>
                                <thead>
                                <tr>
                                    <td colspan="2"><strong>Processor</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>No. of Cores</td>
                                    <td>1</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab-review">
                            <form class="form-horizontal">
                                <div id="review"></div>
                                <h2>Write a review</h2>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-name">Your Name</label>
                                        <input type="text" name="name" value="" id="input-name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-review">Your Review</label>
                                        <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                                        <div class="help-block"><span class="text-danger">Note:</span> HTML is not translated!</div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label">Rating</label>
                                        &nbsp;&nbsp;&nbsp; Bad&nbsp;
                                        <input type="radio" name="rating" value="1" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="2" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="3" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="4" />
                                        &nbsp;
                                        <input type="radio" name="rating" value="5" />
                                        &nbsp;Good</div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-sm-12">
                                        <label class="control-label" for="input-captcha">Enter the code in the box below</label>
                                        <input type="text" name="captcha" value="" id="input-captcha" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12"> <img src="index.php?route=tool/captcha" alt="" id="captcha" /> </div>
                                </div>
                                <div class="buttons">
                                    <div class="pull-right">
                                        <button type="button" id="button-review" data-loading-text="Loading..." class="btn btn-primary">Continue</button>
                                    </div>
                                </div>
                            </form>
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
    $('.date').datetimepicker({
        pickTime: false
    });

    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });

    $('.time').datetimepicker({
        pickDate: false
    });

    $('button[id^=\'button-upload\']').on('click', function() {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        $('#form-upload input[name=\'file\']').on('change', function() {
            $.ajax({
                url: 'index.php?route=tool/upload',
                type: 'post',
                dataType: 'json',
                data: new FormData($(this).parent()[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');
                },
                success: function(json) {
                    $('.text-danger').remove();

                    if (json['error']) {
                        $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $(node).parent().find('input').attr('value', json['code']);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
    //--></script>
<script type="text/javascript"><!--
    $('#review').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#review').fadeOut('slow');

        $('#review').load(this.href);

        $('#review').fadeIn('slow');
    });

    $('#review').load('index.php?route=product/product/review&product_id=43');

    $('#button-review').on('click', function() {
        $.ajax({
            url: 'index.php?route=product/product/write&product_id=43',
            type: 'post',
            dataType: 'json',
            data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
            beforeSend: function() {
                $('#button-review').button('loading');
            },
            complete: function() {
                $('#button-review').button('reset');
                $('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
                $('input[name=\'captcha\']').val('');
            },
            success: function(json) {
                $('.alert-success, .alert-danger').remove();

                if (json['error']) {
                    $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').prop('checked', false);
                    $('input[name=\'captcha\']').val('');
                }
            }
        });
    });

    $(document).ready(function() {
        $('.thumbnails').magnificPopup({
            type:'image',
            delegate: 'a',
            gallery: {
                enabled:true
            }
        });
    });
    //--></script>

