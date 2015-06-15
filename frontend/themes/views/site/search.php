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
        <div id="content" class="col-sm-9"><ul class="breadcrumb">
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=common/home"><i class="fa fa-home"></i></a></li>
                <li><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;search=lu">Search</a></li>
            </ul>      <h1 class="page-title">Search - lu</h1>
            <fieldset>
                <label class="control-label " for="input-search"><b>Lựa chọn tìm kiếm</b></label>
                <div class="row">
                    <div class="col-sm-4">
                        <input type="text" name="search" value="lu" placeholder="Keywords" id="input-search" class="form-control">
                    </div>

                    <div class="col-sm-3 sortcat">
                        <select name="category_id" class="form-control " style="-webkit-appearance: menulist-button; width: 226px; position: absolute; opacity: 0; height: 30px; font-size: 14px;">
                            <option value="0">All Categories</option>
                            <option value="20">Electronics</option>
                            <option value="59">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Laptops</option>
                            <option value="60">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tablates</option>
                            <option value="26">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PC</option>
                            <option value="61">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dell</option>
                            <option value="62">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Samsung</option>
                            <option value="27">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mac</option>
                            <option value="18">Parts &amp; Accessories</option>
                            <option value="46">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Macs</option>
                            <option value="45">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Windows</option>
                            <option value="25">Components</option>
                            <option value="29">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mice and Trackballs</option>
                            <option value="28">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Monitors</option>
                            <option value="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Printers</option>
                            <option value="31">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Scanners</option>
                            <option value="32">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Web Cameras</option>
                            <option value="57">Interiors</option>
                            <option value="17">Kitchen</option>
                            <option value="24">House Holds</option>
                        </select>
                        <span class="customSelect form-control" style="display: inline-block;"><span class="customSelectInner" style="width: 210px; display: inline-block;">All Categories</span></span>
                    </div>
                    <div class="col-sm-3 subcategory">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="description" value="1" id="description">
                            Tìm kiếm với phần miêu tả</label>
                    </div>
                </div>
            </fieldset>
            <input type="button" value="Tìm kiếm" id="button-search" class="btn btn-primary">
            <h2>Kết quả tìm kiếm</h2>


            <div class="category_filter">
                <div class="col-md-4 btn-list-grid">
                    <div class="btn-group">
                        <button type="button" id="list-view" class="btn btn-default list" data-toggle="tooltip" title="" data-original-title="List"><i class="fa fa-th-list"></i></button>
                        <button type="button" id="grid-view" class="btn btn-default grid active" data-toggle="tooltip" title="" data-original-title="Grid"><i class="fa fa-th"></i></button>
                    </div>
                </div>
                <div class="pagination-right">
                    <div class="sort-by-wrapper">
                        <div class="col-md-2 text-right sort-by">
                            <label class="control-label" for="input-sort">Sắp xếp theo:</label>
                        </div>
                        <div class="col-md-3 text-right sort">
                            <select id="input-sort" class="form-control hasCustomSelect" onchange="location = this.value;" style="-webkit-appearance: menulist-button; width: 170px; position: absolute; opacity: 0; height: 28px; font-size: 14px;">
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=p.sort_order&amp;order=ASC&amp;search=lu" selected="selected">Default</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=pd.name&amp;order=ASC&amp;search=lu">Name (A - Z)</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=pd.name&amp;order=DESC&amp;search=lu">Name (Z - A)</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=p.price&amp;order=ASC&amp;search=lu">Price (Low &gt; High)</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=p.price&amp;order=DESC&amp;search=lu">Price (High &gt; Low)</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=rating&amp;order=DESC&amp;search=lu">Rating (Highest)</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=rating&amp;order=ASC&amp;search=lu">Rating (Lowest)</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=p.model&amp;order=ASC&amp;search=lu">Model (A - Z)</option>
                                <option value="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/search&amp;sort=p.model&amp;order=DESC&amp;search=lu">Model (Z - A)</option>
                            </select><span class="customSelect form-control" style="display: inline-block;"><span class="customSelectInner" style="width: 154px; display: inline-block;">Default</span></span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row productlist-grid">


                <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="product-thumb product-block">
                        <div class="product-block-inner">
                            <div class="image">
                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30&amp;search=lu"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/12-220x200.jpg" alt="Aliquam volutpat" title="Aliquam volutpat" class="img-responsive">
                                    <div class="saleback">
                                        <span class="saleicon sale">Sale</span>
                                    </div>
                                </a>
                            </div>
                            <div class="product-details">
                                <div class="caption">
                                    <div class="left">
                                        <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=30&amp;search=lu" title="Aliquam volutpat">
                                                Aliquam volutpat				</a>
                                        </h4>
                                        <div class="desc">Sample Lorem Ipsum Text

                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at ante. Mau..</div>



                                        <div class="compare-wishlist">
                                            <div class="wishlist-btn">
                                                <button type="button" class="wishlist" title="Add to Wish List" onclick="wishlist.add('30');">Add to Wish List</button>
                                            </div>
                                            <div class="compare-btn">
                                                <button type="button" class="compare" title="Add to Compare" onclick="compare.add('30');">Add to Compare</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right">

                                        <div class="price">
                                            <span class="price-old">$122.00</span><span class="price-new">$98.00</span>
                                            <span class="price-tax">Ex Tax: $80.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-group">
                                    <button type="button" title="Add to Cart" class="addtocart" onclick="cart.add('30');"><span>Add to Cart</span></button>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="product-thumb product-block">
                        <div class="product-block-inner">
                            <div class="image">
                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=32&amp;search=lu"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/22-220x200.jpg" alt="Nascetur ridiculus mus" title="Nascetur ridiculus mus" class="img-responsive">

                                </a>
                            </div>
                            <div class="product-details">
                                <div class="caption">
                                    <div class="left">
                                        <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=32&amp;search=lu" title="Nascetur ridiculus mus">
                                                Nascetur ridiculus m..				</a>
                                        </h4>
                                        <div class="desc">Sample Lorem Ipsum Text


                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at ante. M..</div>



                                        <div class="compare-wishlist">
                                            <div class="wishlist-btn">
                                                <button type="button" class="wishlist" title="Add to Wish List" onclick="wishlist.add('32');">Add to Wish List</button>
                                            </div>
                                            <div class="compare-btn">
                                                <button type="button" class="compare" title="Add to Compare" onclick="compare.add('32');">Add to Compare</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right">

                                        <div class="price">
                                            $122.00                                                      <span class="price-tax">Ex Tax: $100.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-group">
                                    <button type="button" title="Add to Cart" class="addtocart" onclick="cart.add('32');"><span>Add to Cart</span></button>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="product-thumb product-block">
                        <div class="product-block-inner">
                            <div class="image">
                                <a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=49&amp;search=lu"><img src="http://opencart-demos.org/OPC05/OPC050107/image/cache/catalog/8-220x200.jpg" alt="Tellus volutpat ius" title="Tellus volutpat ius" class="img-responsive">

                                </a>
                            </div>
                            <div class="product-details">
                                <div class="caption">
                                    <div class="left">
                                        <h4><a href="http://opencart-demos.org/OPC05/OPC050107/index.php?route=product/product&amp;product_id=49&amp;search=lu" title="Tellus volutpat ius">
                                                Tellus volutpat ius				</a>
                                        </h4>
                                        <div class="desc">Sample Lorem Ipsum Text




                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at ant..</div>



                                        <div class="compare-wishlist">
                                            <div class="wishlist-btn">
                                                <button type="button" class="wishlist" title="Add to Wish List" onclick="wishlist.add('49');">Add to Wish List</button>
                                            </div>
                                            <div class="compare-btn">
                                                <button type="button" class="compare" title="Add to Compare" onclick="compare.add('49');">Add to Compare</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right">

                                        <div class="price">
                                            $241.99                                                      <span class="price-tax">Ex Tax: $199.99</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-group">
                                    <button type="button" title="Add to Cart" class="addtocart" onclick="cart.add('49');"><span>Add to Cart</span></button>

                                </div>

                            </div>
                        </div>
                    </div>
                </div><div class="clearfix visible-lg"></div>
            </div>
            <div class="pagination-wrapper">
                <div class="col-sm-6 text-left page-link"></div>
                <div class="col-sm-6 text-right page-result">Showing 1 to 3 of 3 (1 Pages)</div>
            </div>
        </div>
    </div>
</div>
