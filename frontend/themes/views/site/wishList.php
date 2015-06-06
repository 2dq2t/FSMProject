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
require('_navBar.php');
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?php echo Yii::$app->request->baseUrl . "/index.php?r=site/wish-list"; ?>" title="Danh mục yêu thích">Danh mục yêu thích</a>
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
            <div class="row">
                <h1 id="wishlist-total" class="page-title">Danh mục yêu thích <?php if (!Yii::$app->user->isGuest) {
                        $number_product = Yii::$app->NavBarMenu->numberProductWishList(Yii::$app->user->identity->getId());
                        echo " (" . $number_product . ")";
                    } ?>
                </h1>

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
                            <tr id="<?= "product".$product['product_id']?>">
                                <td class="text-center  col-sm-2">
                                    <a href="<?= $baseUrl . "/index.php?r=site/view-detail&product=" . $product['product_name'] ?>"><img
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
                                        echo $new_price . " VND";
                                    } else
                                        echo $product['product_price'] . " VND";
                                    ?></td>
                                <td class="text-center"><?= $product['product_unit'] ?></td>
                                <td class="text-center">
                                    <button type="button" data-toggle="tooltip" title="Xóa khỏi danh mục yêu thích"
                                            class="btn btn-danger"
                                            onclick="wishlist.remove(<?= $product['product_id'] ?>);"><i
                                            class="fa fa-times-circle"></i></button>

                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
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


