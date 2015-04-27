<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- START ROW -->
    <div class="row">

        <!-- NEW COL START -->
        <article class="col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <form action="" class="smart-form">
                            <header>
                                <h4>Product:</h4> <?= $model->name ?>
                            </header>

                            <?php
                            $categoryName = \backend\models\Category::findOne($model->category_id)['name'];
                            $images = \backend\models\Image::find()->where(['product_id' => $model->id])->one();
                            $actual_link = "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]";
                            $url = sprintf(
                                "%s://%s%s",
                                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                                $_SERVER['SERVER_NAME'],
                                dirname(dirname(dirname($_SERVER['REQUEST_URI'])))
                            );

                            $path = $url . '/' . $images['path'];
                            ?>

                            <fieldset>
                                <div class="row">
                                    <section class="col col-2">
                                        <img src="<?= $path ?>" alt="" class="img-thumbnail img-responsive center-block"/>
                                    </section>
                                    <section class="col col-10">
                                        <div class="col-md-12">
                                            <h5>Category: </h5><?= $categoryName ?>
                                        </div>
                                    </section>
                                    <section class="col col-10">
                                        <div class="col-md-12">
                                            <h5>Description:</h5><p><?= $model->description ?></p>
                                        </div>
                                    </section>
                                    <section class="col col-10">
                                        <div class="col-md-12">
                                            <h5>Sold: </h5><p><?= $model->sold ?></p>
                                        </div>
                                    </section>
                                </div>

                            </fieldset>

                        </form>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
        <!-- END COL -->

    </div>

    <!-- END ROW -->

</section>
<!-- end widget grid -->