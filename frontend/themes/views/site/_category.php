<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 26/05/2015
 * Time: 12:31 SA
 */
?>
<div class="box side-menu animate-dropdown">
    <div class="box-heading">Categories</div>
    <div class="box-content ">
        <nav class=" box-category yamm megamenu-horizontal" role="navigation">
            <ul class="nav">
                <?php $result = array();
                foreach ($modelCategory as $item) {
                    $cat_name = $item['categoryname'];
                    $product_name = $item['productname'];
                    $product_id =$item['productId'];
                    $result[$cat_name][] = $product_name;
                }
                ?>
                <?php foreach (array_keys($result) as $categories): ?>
                    <li class="dropdown menu-item">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $categories ?></a>
                        <ul class="dropdown-menu mega-menu">
                            <li class="yamm-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <ul class="list-unstyled">
                                            <?php foreach ($result[$categories] as $key) { ?>
                                                <li><a href="/FSMProject/frontend/web/index.php?r=site/view-detail&product=<?php echo $key;?>"><?= $key ?></a></li>
                                            <?php }; ?>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li><!-- /.menu-item -->
                <?php endforeach; ?>
            </ul>
        </nav>

    </div>
</div>