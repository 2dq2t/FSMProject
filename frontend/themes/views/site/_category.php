<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 26/05/2015
 * Time: 12:31 SA
 */
?>
<div class="box">
    <div class="box-heading">Danh mục</div>
    <div class="box-content ">
        <ul class="box-category treeview-list treeview">
            <?php $result = array();
            foreach ($modelCategory as $item) {
                $cat_name = $item['categoryname'];
                $product_name = $item['productname'];
                $result[$cat_name][] = $product_name;
            }
            ?>
            <?php foreach (array_keys($result) as $categories): ?>
            <li>
                <a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/category&category=".$categories?>"><?= $categories ?></a>
                <ul>
                    <?php foreach ($result[$categories] as $key) { ?>
                    <li>
                        <a href="<?= Yii::$app->request->baseUrl."/index.php?r=site/view-detail&product=".$key?>"><?= $key ?></a>
                    </li>
                    <?php }; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
