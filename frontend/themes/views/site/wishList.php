<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
$this->title = "Yêu thích";
?>
<?php
require('_header.php');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=site/wish-list"; ?>"
               title="Danh mục yêu thích">Danh mục yêu thích</a>
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
        <div id="content" class="col-sm-9">
                <h1 id="wish_list" class="page-title">Danh mục yêu thích <?php if (!Yii::$app->user->isGuest) {
                        $number_product = Yii::$app->Header->numberProductWishList(Yii::$app->user->identity->getId());
                        echo " (" . $number_product . ")";
                    } ?>
                </h1>
                <?php if(empty($wish_list_product)){?>
                    <p> Không có sản phẩm nào trong danh mục yêu thích </p>
                    <div class="buttons">
                        <div class="pull-right"><a
                                href="<?= $baseUrl ?>"
                                class="btn btn-primary">Về trang chủ</a></div>
                    </div>
                <?php }else{ ?>
                <div class="table-responsive">
                    <table class="table table-bordered shopping-cart">
                        <thead>
                        <tr>
                            <td class="text-center col-sm-2">Hình ảnh</td>
                            <td class="text-center">Tên sản phẩm</td>
                            <td class="text-center">Giá sản phẩm</td>
                            <td class="text-center">Khối lượng</td>
                            <td class="text-center">Hành động</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($wish_list_product as $product) { ?>
                            <tr id="<?= "product" . $product['product_id'] ?>">
                                <td class="text-center  col-sm-2 row-sm-2">
                                    <a href="<?= $baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><img style="width: 94px;height: 94px"
                                            src="<?= $product['product_image'] ?>" alt="<?= $product['product_name'] ?>"
                                            title="<?= $product['product_name'] ?>" class="img-thumbnail"/></a>
                                </td>
                                <td class="text-center"><a
                                        href="<?= $baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><?= $product['product_name'] ?></a>
                                    <br/>
                                    <small>Số
                                        lượng: <?php if ($product['product_quantity'] - $product['product_sold'] > 0) echo $product['product_quantity'] - $product['product_sold'];
                                        else echo "Trong kho" ?>
                                    </small>
                                </td>
                                <td class="text-center"><?php
                                    if (!empty($product['product_offer'])) {
                                        $new_price = $product['product_price'] * (100 / $product['product_offer']);
                                        echo number_format($new_price) . " VND";
                                    } else
                                        echo number_format($product['product_price']) . " VND";
                                    ?></td>
                                <td class="text-center"><?= $product['product_unit'] ?></td>
                                <td class="text-center">
                                    <button id="<?= "remove" . $product['product_id'] ?>" type="button"
                                            data-toggle="tooltip" title="Xóa khỏi danh mục yêu thích"
                                            class="btn btn-danger"
                                            onclick="wishlist.remove(<?= $product['product_id'] ?>);"><i
                                            class="fa fa-times-circle"></i></button>

                                </td>
                            </tr>
                        </tbody>
                        <?php } ?>
                    </table>
                </div>
                <?php } ?>

            <div class="box">

                <div class="box-heading">Sản phẩm đã xem gần đây</div>
                <div class="box-content">
                    <div id="products-related" class="related-products">

                        <div class="customNavigation">
                            <a class="prev">&nbsp;</a>
                            <a class="next">&nbsp;</a>
                        </div>


                        <div class="box-product product-carousel" id="related-carousel">

                            <?php  if( count($product_session) >0)foreach($product_session as $product){?>
                                <div class="slider-item">
                                    <div class="product-block product-thumb transition">
                                        <div class="product-block-inner ">
                                            <div class="image">
                                                <a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/view-detail&product=".$product['product_name']?>"><img
                                                        src="<?=$product['product_image'] ?>"
                                                        alt="<?=$product['product_name']?>" title="<?=$product['product_name']?>"
                                                        class="img-responsive"/></a>
                                                <?php
                                                if(isset($product['product_offer'])&&$product['product_offer'] >0)
                                                    echo "<span class='saleicon sale'>Sale</span>";

                                                if(isset($product['product_rating'])&&$product['product_rating']>0) {
                                                    echo "<div class='rating' >";
                                                    for($i=0;$i<5;$i++){
                                                        if($i<$product['product_rating']){
                                                            echo "<span class='fa fa-stack' ><i class='fa fa-star fa-stack-2x'' ></i ></span >";
                                                        }
                                                        else
                                                            echo "<span class='fa fa-stack' ><i class='fa fa-star off fa-stack-2x' ></i ></span >";
                                                    }
                                                    echo "</div >";
                                                }
                                                ?>
                                            </div>
                                            <div class="product-details">
                                                <div class="caption">
                                                    <h4>
                                                        <a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/view-detail&product=".$product['product_name']?>"
                                                           title="<?=$product['product_name']?>">
                                                            <?=$product['product_name']?></a>
                                                    </h4>

                                                    <div class="price">
                                                        <?php
                                                        if(!empty($product['product_offer'])){
                                                            echo "<span class='price-old'>".number_format($product['product_price'])." VND</span>";
                                                            $new_price = $product['product_price']*(100/$product['product_offer']);
                                                            echo "<span class='price-new'>".number_format($new_price)." VND</span>";
                                                            echo "<span class='price-tax'>Thuế: ".number_format($product['product_tax'])." VND</span>";
                                                        }
                                                        else{
                                                            echo number_format($product['product_price'])." VND";
                                                            echo "<span class='price-tax'>Thuế: ".number_format($product['product_tax'])." VND</span>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="button-group">
                                                    <button type="button" title="Add to Cart" class="addtocart"
                                                            onclick="cart.add('<?=$product['product_id']?>');"><span>Thêm vào giỏ hàng</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
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


