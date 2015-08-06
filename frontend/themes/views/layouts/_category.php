<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 26/05/2015
 * Time: 12:31 SA
 */
?>
<div class="box">
    <div class="box-heading"><?= Yii::t('app', 'CategoryLabel') ?></div>
    <div class="box-content ">
        <ul class="box-category treeview-list treeview">
            <?php
            $categories = Yii::$app->Category->getCategory();
            $result = array();
            foreach ($categories as $item) {
                $cat_name = $item['categoryname'];
                $product_name = $item['productname'];
                $result[$cat_name][] = $product_name;
            }
            ?>
            <?php foreach (array_keys($result) as $category): ?>
                <li>
                    <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=product/get-product-category&category=" . $category ?>"><?= ucfirst($category) ?></a>
                    <ul>
                        <?php foreach ($result[$category] as $key) { ?>
                            <li>
                                <a href="<?= Yii::$app->request->baseUrl . "/index.php?r=product/view-detail&product=" . $key ?>"><?= ucfirst($key) ?></a>
                            </li>
                        <?php }; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
