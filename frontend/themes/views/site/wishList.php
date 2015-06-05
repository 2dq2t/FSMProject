<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 25/05/2015
 * Time: 10:41 CH
 */
$baseUrl = Yii::$app->request->baseUrl;
$this->title = $product_detail['name'];
?>
<?php echo $this->render('_navbar', [
    'categories' => $categories,
]);
?>
<div class="container content-inner">
    <ul class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li>
            <a href="<?php echo $baseUrl . 'index.php?r=site/view-detail&product=' . $product_detail['name'] ?>"><?php echo $product_detail['name'] ?></a>
        </li>
    </ul>
    <div class="row content-subinner">
        <column id="column-left" class="col-sm-3 hidden-xs">
            <?php echo $this->render('_category', [
                'categories' => $categories,
            ]);
            ?>
            <?php echo $this->render('_leftBanner');
            ?>

            <?php echo $this->render('_specialProduct', ['special_product' => $special_product]);
            ?>
            <?php echo $this->render('_bestSeller', ['best_seller' => $best_seller]);
            ?>
        </column>
        <div id="content" class="productpage col-sm-9">
            <div class="row">

        </div>

    </div>
</div>

<script type="text/javascript"><!--
    $('select[name=\'recurring_id\'], input[name="quantity"]').change(function () {
        $.ajax({
            url: 'index.php?route=product/product/getRecurringDescription',
            type: 'post',
            data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
            dataType: 'json',
            beforeSend: function () {
                $('#recurring-description').html('');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['success']) {
                    $('#recurring-description').html(json['success']);
                }
            }
        });
    });
    //--></script>
<script type="text/javascript"><!--
    $('#button-cart').on('click', function () {
        $.ajax({
            url: 'index.php?r=site/add-to-cart',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-cart').button('loading');
            },
            complete: function () {
                $('#button-cart').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                console.log(json);
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

                    $('html, body').animate({scrollTop: 0}, 'slow');

                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            }
        });
    });
    //--></script>

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
<script type="text/javascript">
    $(document).ready(function () {
        $('#star').raty({
            score:<?=$rating_average ?>,
            click: function (score, evt) {
                var product_id = document.getElementById('product_id').value;
                $.ajax({
                    url: 'index.php?r=site/rate',
                    type: 'post',
                    data: {score: score, product_id: product_id},
                    dataType: 'json',
                    success: function (data) {
                        $('.alert').remove();
                        var json = $.parseJSON(data)
                        if (json['success']) {
                            $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            //$('#star').raty('reload');
                            $('#star').raty('readOnly', true);
                        }

                        if (json['error']) {
                            $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }

                        $('html, body').animate({scrollTop: 0}, 'slow');
                    }
                });
            }
        });
    });
</script>

