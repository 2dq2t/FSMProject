<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app','HomeTitle');
?>
<div class="site-index">


    <?php
    \miloschuman\highcharts\HighchartsAsset::register($this)->withScripts(['modules/exporting', 'modules/drilldown']);
    ?>
    <?php if (Yii::$app->user->can('/site/sale')) {?>
        <!-- BEGIN DASHBOARD FOR SALE-->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
                <div class="dashboard-stat2">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-green-sharp"><?= number_format($total_profit_by_last_six_month['Total Profit'], 2)?><small class="font-green-sharp">&#273;</small></h3>
                            <small>TOTAL PROFIT LAST SIX MONTH</small>
                        </div>
                        <div class="icon">
                            <i class="icon-pie-chart"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat2">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-red-haze"><?= number_format($total_order_by_last_six_month['numberOrder'], 0)?></h3>
                            <small>TOTAL ORDERS LAST SIX MONTH</small>
                        </div>
                        <div class="icon">
                            <i class="icon-like"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat2">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-blue-sharp"><?= number_format($total_profit_by_last_six_month['Total Profit']/$total_order_by_last_six_month['numberOrder'], 2)?><small class="font-blue-sharp">&#273;</small></h3>
                            <small>AVERAGE ORDER LAST SIX MONTH</small>
                        </div>
                        <div class="icon">
                            <i class="icon-basket"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption">
                            <i class="icon-bar-chart font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Overview</span>
                            <span class="caption-helper"></span>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#weekly_overview" data-toggle="tab" id="statistics_amounts_tab">
                                    <?= Yii::t('app','Weekly')?> </a>
                            </li>
                            <li>
                                <a href="#monthly_overview" data-toggle="tab">
                                    <?= Yii::t('app', 'Monthly')?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">

                            <div class="tab-pane active" id="weekly_overview">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#weekly_overview_1" data-toggle="tab">
                                                <?= Yii::t('app', 'Top Selling')?> </a>
                                        </li>
                                        <li>
                                            <a href="#weekly_overview_2" data-toggle="tab">
                                                <?= Yii::t('app', 'Most Buyer')?> </a>
                                        </li>
                                        <li>
                                            <a href="#weekly_overview_3" data-toggle="tab">
                                                <?= Yii::t('app', 'Last 10 Order')?> </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="weekly_overview_1">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <?= Yii::t('app', 'Product Name')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Price')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Sold')?>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($top_sale_by_weekly as $top_sale_by_week) {?>
                                                        <tr>
                                                            <td>
                                                                <a href="../../frontend/web/index.php?r=product/view-detail&product=<?=$top_sale_by_week['name']?>" onclick="window.open(this.href,'_blank');return false;">
                                                                    <?= $top_sale_by_week['name']?> </a>
                                                            </td>
                                                            <td>
                                                                <?= number_format($top_sale_by_week['sell_price'], 2)?>
                                                            </td>
                                                            <td>
                                                                <?= number_format($top_sale_by_week['Total Quantity'])?>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="weekly_overview_2">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <?= Yii::t('app', 'Customer Name')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Total Orders')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Total Amount')?>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($top_customers_orders_by_weekly as $top_customers_order_by_weekly) {?>
                                                        <tr>
                                                            <td>
                                                                <?= $top_customers_order_by_weekly['full_name']?>
                                                            </td>
                                                            <td>
                                                                <?= $top_customers_order_by_weekly['Total Orders']?>
                                                            </td>
                                                            <td>
                                                                <?= number_format($top_customers_order_by_weekly['Total Amount'], 2)?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="weekly_overview_3">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <?= Yii::t('app', 'Customer Name')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Date')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Total Amount')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Order Status')?>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($last_10_orders_by_weekly as $last_10_order_by_weekly) {?>
                                                        <tr>
                                                            <td>
                                                                <?=$last_10_order_by_weekly['full_name']?>
                                                            </td>
                                                            <td>
                                                                <?= date('d/m/Y', $last_10_order_by_weekly['order_date'])?>
                                                            </td>
                                                            <td>
                                                                <?= number_format($last_10_order_by_weekly['Total Amount'], 2)?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($last_10_order_by_weekly['order_status_id'] === 1) {
                                                                    $label_class = 'label-info';
                                                                    $value = $last_10_order_by_weekly['name'];
                                                                } else if ($last_10_order_by_weekly['order_status_id'] == 2) {
                                                                    $label_class = 'label-primary';
                                                                    $value = $last_10_order_by_weekly['name'];
                                                                } else if ($last_10_order_by_weekly['order_status_id'] == 3) {
                                                                    $label_class = 'label-danger';
                                                                    $value = $last_10_order_by_weekly['name'];
                                                                } else {
                                                                    $label_class = 'label-success';
                                                                    $value = $last_10_order_by_weekly['name'];
                                                                }
                                                                ?>
                                                                <span class="label label-sm <?=$label_class?>">
													<?= $value?> </span>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END WEEKLY OVERVIEW -->
                            <div class="tab-pane" id="monthly_overview">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#monthly_overview_1" data-toggle="tab">
                                                <?= Yii::t('app', 'Top Selling')?> </a>
                                        </li>
                                        <li>
                                            <a href="#monthly_overview_2" data-toggle="tab">
                                                <?= Yii::t('app', 'Most Buyer')?> </a>
                                        </li>
                                        <li>
                                            <a href="#monthly_overview_3" data-toggle="tab">
                                                <?= Yii::t('app', 'Last 10 Order')?> </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="monthly_overview_1">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <?= Yii::t('app', 'Product Name')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Price')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Sold')?>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($top_sale_by_monthly as $top_sale_by_month) {?>
                                                        <tr>
                                                            <td>
                                                                <a href="../../frontend/web/index.php?r=product/view-detail&product=<?=$top_sale_by_month['name']?>" onclick="window.open(this.href,'_blank');return false;">
                                                                    <?= $top_sale_by_month['name']?> </a>
                                                            </td>
                                                            <td>
                                                                <?= number_format($top_sale_by_month['sell_price'], 2)?>
                                                            </td>
                                                            <td>
                                                                <?= number_format($top_sale_by_month['Total Quantity'])?>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="monthly_overview_2">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <?= Yii::t('app', 'Customer Name')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Total Orders')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Total Amount')?>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($top_customers_orders_by_monthly as $top_customers_order_by_monthly) {?>
                                                        <tr>
                                                            <td>
                                                                <?= $top_customers_order_by_monthly['full_name']?>
                                                            </td>
                                                            <td>
                                                                <?= $top_customers_order_by_monthly['Total Orders']?>
                                                            </td>
                                                            <td>
                                                                <?= number_format($top_customers_order_by_monthly['Total Amount'], 2)?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="monthly_overview_3">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <?= Yii::t('app', 'Customer Name')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Date')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Total Amount')?>
                                                        </th>
                                                        <th>
                                                            <?= Yii::t('app', 'Order Status')?>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($last_10_orders_by_monthly as $last_10_order_by_monthly) {?>
                                                        <tr>
                                                            <td>
                                                                <?=$last_10_order_by_monthly['full_name']?>
                                                            </td>
                                                            <td>
                                                                <?= date('d/m/Y', $last_10_order_by_monthly['order_date'])?>
                                                            </td>
                                                            <td>
                                                                <?= number_format($last_10_order_by_monthly['Total Amount'], 2)?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($last_10_order_by_monthly['order_status_id'] === 1) {
                                                                    $label_class = 'label-info';
                                                                    $value = $last_10_order_by_monthly['name'];
                                                                } else if ($last_10_order_by_monthly['order_status_id'] == 2) {
                                                                    $label_class = 'label-primary';
                                                                    $value = $last_10_order_by_monthly['name'];
                                                                } else if ($last_10_order_by_monthly['order_status_id'] == 3) {
                                                                    $label_class = 'label-danger';
                                                                    $value = $last_10_order_by_monthly['name'];
                                                                } else {
                                                                    $label_class = 'label-success';
                                                                    $value = $last_10_order_by_monthly['name'];
                                                                }
                                                                ?>
                                                                <span class="label label-sm <?=$label_class?>">
													<?= $value?> </span>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END MONTHLY OVERVIEW -->
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
            <div class="col-md-6">
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption">
                            <i class="icon-share font-red-sunglo"></i>
                            <span class="caption-subject font-red-sunglo bold uppercase">Revenue</span>
                            <span class="caption-helper"></span>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#last_six_month_revenue" data-toggle="tab">
                                    <?= Yii::t('app', 'Amounts')?>
                                </a>
                            </li>
                            <!--                        <li class="">-->
                            <!--                            <a href="#last_six_month_orders" data-toggle="tab">-->
                            <!--                                --><?//= Yii::t('app', 'Orders')?><!-- </a>-->
                            <!--                        </li>-->
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content" >
                            <div class="tab-pane active" id="last_six_month_revenue">
                                <div class="row">
                                    <div class="col-sx-9">
                                        <?php
                                        $new_revenue_of_week_by_last_six_month = [];
                                        $new_revenue_by_last_six_month = [];
                                        $tmp_revenue_of_week_by_last_six_month = [];

                                        var_dump($revenue_by_week_of_last_six_month);
                                        foreach($revenue_by_week_of_last_six_month as $key => $value) {
                                            if (empty($tmp_revenue_of_week_by_last_six_month)) {
                                                $tmp_revenue_of_week_by_last_six_month[$value['month']][] = [Yii::t('app','Week'). $value['weekOfMonth'],intval($value['Total'])];
                                            } else if(isset($tmp_revenue_of_week_by_last_six_month[$value['month']]) &&
                                                $tmp_revenue_of_week_by_last_six_month[$value['month']] == $value['month']){
                                                $tmp_revenue_of_week_by_last_six_month[$value['month']][] = [Yii::t('app','Week'). $value['weekOfMonth'], intval($value['Total'])];
                                            } else {
                                                $tmp_revenue_of_week_by_last_six_month[$value['month']][] = [Yii::t('app','Week'). $value['weekOfMonth'], intval($value['Total'])];
                                            }
                                        }

                                        foreach($revenue_by_week_of_last_six_month as $key => $value) {
                                            if (!isset($new_revenue_of_week_by_last_six_month['series'])) {
                                                $new_revenue_of_week_by_last_six_month['series'][] = [
                                                    'name' => Yii::t('app', 'Amount'),
                                                    'id' => $value['month'],
                                                    'data' => $tmp_revenue_of_week_by_last_six_month[$value['month']]
                                                ];
                                                continue;
                                            }
                                            foreach($new_revenue_of_week_by_last_six_month['series'] as $key2 => $value2){
                                                if ($value2['id'] != $value['month']) {
                                                    $new_revenue_of_week_by_last_six_month['series'][] = [
                                                        'name' => Yii::t('app', 'Amount'),
                                                        'id' => $value['month'],
                                                        'data' => $tmp_revenue_of_week_by_last_six_month[$value['month']]
                                                    ];
                                                };
                                            }
                                        }
                                        $new_revenue_by_week['name'] = Yii::t('app', 'Amount');

                                        foreach($revenue_by_last_six_month as $key => $value) {
                                            $new_revenue_by_last_six_month['data'][] = [
                                                'name' => Yii::t('app', 'Month') . ' ' . $value['month'],
                                                'y' => intval($value['Total']),
                                                'drilldown' => $value['month']
                                            ];
                                        }

                                        $new_revenue_by_last_six_month['name'] = Yii::t('app', 'Amount');

                                        echo \miloschuman\highcharts\Highcharts::widget([
                                            'options' => [
                                                'chart' => [
                                                    'type' => 'column'
                                                ],
                                                'title' =>  [
                                                    'text'=> Yii::t('app', 'Last six month revenue.')
                                                ],
                                                'credits' => [
                                                    'enabled' => false
                                                ],
                                                'xAxis' => [
                                                    'type' => 'category',

                                                ],
                                                'yAxis'=> [
                                                    'min' => 0,
                                                    'title'=> [
                                                        'text' => Yii::t('app', 'Money (dong)')
                                                    ],
                                                    'labels' => [
                                                        'format' => '{value:,.0f}'
                                                    ]
                                                ],
                                                'legend' => [
                                                    'enabled' => true
                                                ],
                                                'tooltip' => [
                                                    'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
                                                    'pointFormat' => '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.2f}d</b>',
                                                ],
                                                'plotOptions' => [
                                                    'series' => [
                                                        'borderWidth' => 0,
                                                        'dataLabels' => [
                                                            'enabled' => true,
                                                            'format' => '{point.y:,.1f}'
                                                        ]
                                                    ]
                                                ],
                                                'series' => [
                                                    $new_revenue_by_last_six_month
                                                ],
                                                'drilldown' => $new_revenue_of_week_by_last_six_month
                                            ]
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="well margin-top-10 no-margin no-border">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-6 text-stat">
										<span class="label label-success">
										Revenue: </span>
                                    <h3><?= $total_revenue_by_last_six_month['Total']?></h3>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-6 text-stat">
										<span class="label label-info">
										Tax: </span>
                                    <h3><?=$total_tax_by_last_six_month['Total']?></h3>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-6 text-stat">
										<span class="label label-warning">
										Orders: </span>
                                    <h3><?=$total_order_by_last_six_month['numberOrder']?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END DASHBOARD FOR SALE-->
    <?php } else if (Yii::$app->user->can('/site/manage')) {?>
    <!-- BEGIN DASHBOARD FOR SALE-->
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp"><?= number_format($total_profit_by_last_six_month['Total Profit'], 2)?><small class="font-green-sharp">&#273;</small></h3>
                        <small>TOTAL PROFIT LAST SIX MONTH</small>
                    </div>
                    <div class="icon">
                        <i class="icon-pie-chart"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <h3 class="font-red-haze"><?= number_format($total_order_by_last_six_month['numberOrder'], 0)?></h3>
                        <small>TOTAL ORDERS LAST SIX MONTH</small>
                    </div>
                    <div class="icon">
                        <i class="icon-like"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp"><?= number_format($total_profit_by_last_six_month['Total Profit']/$total_order_by_last_six_month['numberOrder'], 2)?><small class="font-blue-sharp">&#273;</small></h3>
                        <small>AVERAGE ORDER LAST SIX MONTH</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <!-- Begin: life time stats -->
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption">
                        <i class="icon-bar-chart font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp bold uppercase">Overview</span>
                        <span class="caption-helper"></span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#weekly_overview" data-toggle="tab" id="statistics_amounts_tab">
                                <?= Yii::t('app','Weekly')?> </a>
                        </li>
                        <li>
                            <a href="#monthly_overview" data-toggle="tab">
                                <?= Yii::t('app', 'Monthly')?></a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body">
                    <div class="tab-content">

                        <div class="tab-pane active" id="weekly_overview">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#weekly_overview_1" data-toggle="tab">
                                            <?= Yii::t('app', 'Top Selling')?> </a>
                                    </li>
                                    <li>
                                        <a href="#weekly_overview_2" data-toggle="tab">
                                            <?= Yii::t('app', 'Most Buyer')?> </a>
                                    </li>
                                    <li>
                                        <a href="#weekly_overview_3" data-toggle="tab">
                                            <?= Yii::t('app', 'Last 10 Order')?> </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="weekly_overview_1">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <?= Yii::t('app', 'Product Name')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Price')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Sold')?>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($top_sale_by_weekly as $top_sale_by_week) {?>
                                                    <tr>
                                                        <td>
                                                            <a href="../../frontend/web/index.php?r=product/view-detail&product=<?=$top_sale_by_week['name']?>" onclick="window.open(this.href,'_blank');return false;">
                                                                <?= $top_sale_by_week['name']?> </a>
                                                        </td>
                                                        <td>
                                                            <?= number_format($top_sale_by_week['sell_price'], 2)?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($top_sale_by_week['Total Quantity'])?>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="weekly_overview_2">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <?= Yii::t('app', 'Customer Name')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Total Orders')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Total Amount')?>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($top_customers_orders_by_weekly as $top_customers_order_by_weekly) {?>
                                                    <tr>
                                                        <td>
                                                            <?= $top_customers_order_by_weekly['full_name']?>
                                                        </td>
                                                        <td>
                                                            <?= $top_customers_order_by_weekly['Total Orders']?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($top_customers_order_by_weekly['Total Amount'], 2)?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="weekly_overview_3">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <?= Yii::t('app', 'Customer Name')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Date')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Total Amount')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Order Status')?>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($last_10_orders_by_weekly as $last_10_order_by_weekly) {?>
                                                    <tr>
                                                        <td>
                                                            <?=$last_10_order_by_weekly['full_name']?>
                                                        </td>
                                                        <td>
                                                            <?= date('d/m/Y', $last_10_order_by_weekly['order_date'])?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($last_10_order_by_weekly['Total Amount'], 2)?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($last_10_order_by_weekly['order_status_id'] === 1) {
                                                                $label_class = 'label-info';
                                                                $value = $last_10_order_by_weekly['name'];
                                                            } else if ($last_10_order_by_weekly['order_status_id'] == 2) {
                                                                $label_class = 'label-primary';
                                                                $value = $last_10_order_by_weekly['name'];
                                                            } else if ($last_10_order_by_weekly['order_status_id'] == 3) {
                                                                $label_class = 'label-danger';
                                                                $value = $last_10_order_by_weekly['name'];
                                                            } else {
                                                                $label_class = 'label-success';
                                                                $value = $last_10_order_by_weekly['name'];
                                                            }
                                                            ?>
                                                            <span class="label label-sm <?=$label_class?>">
													<?= $value?> </span>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END WEEKLY OVERVIEW -->
                        <div class="tab-pane" id="monthly_overview">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#monthly_overview_1" data-toggle="tab">
                                            <?= Yii::t('app', 'Top Selling')?> </a>
                                    </li>
                                    <li>
                                        <a href="#monthly_overview_2" data-toggle="tab">
                                            <?= Yii::t('app', 'Most Buyer')?> </a>
                                    </li>
                                    <li>
                                        <a href="#monthly_overview_3" data-toggle="tab">
                                            <?= Yii::t('app', 'Last 10 Order')?> </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="monthly_overview_1">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <?= Yii::t('app', 'Product Name')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Price')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Sold')?>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($top_sale_by_monthly as $top_sale_by_month) {?>
                                                    <tr>
                                                        <td>
                                                            <a href="../../frontend/web/index.php?r=product/view-detail&product=<?=$top_sale_by_month['name']?>" onclick="window.open(this.href,'_blank');return false;">
                                                                <?= $top_sale_by_month['name']?> </a>
                                                        </td>
                                                        <td>
                                                            <?= number_format($top_sale_by_month['sell_price'], 2)?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($top_sale_by_month['Total Quantity'])?>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="monthly_overview_2">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <?= Yii::t('app', 'Customer Name')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Total Orders')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Total Amount')?>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($top_customers_orders_by_monthly as $top_customers_order_by_monthly) {?>
                                                    <tr>
                                                        <td>
                                                            <?= $top_customers_order_by_monthly['full_name']?>
                                                        </td>
                                                        <td>
                                                            <?= $top_customers_order_by_monthly['Total Orders']?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($top_customers_order_by_monthly['Total Amount'], 2)?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="monthly_overview_3">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <?= Yii::t('app', 'Customer Name')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Date')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Total Amount')?>
                                                    </th>
                                                    <th>
                                                        <?= Yii::t('app', 'Order Status')?>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($last_10_orders_by_monthly as $last_10_order_by_monthly) {?>
                                                    <tr>
                                                        <td>
                                                            <?=$last_10_order_by_monthly['full_name']?>
                                                        </td>
                                                        <td>
                                                            <?= date('d/m/Y', $last_10_order_by_monthly['order_date'])?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($last_10_order_by_monthly['Total Amount'], 2)?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($last_10_order_by_monthly['order_status_id'] === 1) {
                                                                $label_class = 'label-info';
                                                                $value = $last_10_order_by_monthly['name'];
                                                            } else if ($last_10_order_by_monthly['order_status_id'] == 2) {
                                                                $label_class = 'label-primary';
                                                                $value = $last_10_order_by_monthly['name'];
                                                            } else if ($last_10_order_by_monthly['order_status_id'] == 3) {
                                                                $label_class = 'label-danger';
                                                                $value = $last_10_order_by_monthly['name'];
                                                            } else {
                                                                $label_class = 'label-success';
                                                                $value = $last_10_order_by_monthly['name'];
                                                            }
                                                            ?>
                                                            <span class="label label-sm <?=$label_class?>">
													<?= $value?> </span>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END MONTHLY OVERVIEW -->
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
        <div class="col-md-6">
            <!-- Begin: life time stats -->
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo"></i>
                        <span class="caption-subject font-red-sunglo bold uppercase">Revenue</span>
                        <span class="caption-helper"></span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#last_six_month_revenue" data-toggle="tab">
                                <?= Yii::t('app', 'Amounts')?>
                            </a>
                        </li>
                        <!--                        <li class="">-->
                        <!--                            <a href="#last_six_month_orders" data-toggle="tab">-->
                        <!--                                --><?//= Yii::t('app', 'Orders')?><!-- </a>-->
                        <!--                        </li>-->
                    </ul>
                </div>
                <div class="portlet-body">
                    <div class="tab-content" >
                        <div class="tab-pane active" id="last_six_month_revenue">
                            <div class="row">
                                <div class="col-sx-9">
                                    <?php
                                    $new_revenue_of_week_by_last_six_month = [];
                                    $new_revenue_by_last_six_month = [];
                                    $tmp_revenue_of_week_by_last_six_month = [];

                                    var_dump($revenue_by_week_of_last_six_month);
                                    foreach($revenue_by_week_of_last_six_month as $key => $value) {
                                        if (empty($tmp_revenue_of_week_by_last_six_month)) {
                                            $tmp_revenue_of_week_by_last_six_month[$value['month']][] = [Yii::t('app','Week'). $value['weekOfMonth'],intval($value['Total'])];
                                        } else if(isset($tmp_revenue_of_week_by_last_six_month[$value['month']]) &&
                                            $tmp_revenue_of_week_by_last_six_month[$value['month']] == $value['month']){
                                            $tmp_revenue_of_week_by_last_six_month[$value['month']][] = [Yii::t('app','Week'). $value['weekOfMonth'], intval($value['Total'])];
                                        } else {
                                            $tmp_revenue_of_week_by_last_six_month[$value['month']][] = [Yii::t('app','Week'). $value['weekOfMonth'], intval($value['Total'])];
                                        }
                                    }

                                    foreach($revenue_by_week_of_last_six_month as $key => $value) {
                                        if (!isset($new_revenue_of_week_by_last_six_month['series'])) {
                                            $new_revenue_of_week_by_last_six_month['series'][] = [
                                                'name' => Yii::t('app', 'Amount'),
                                                'id' => $value['month'],
                                                'data' => $tmp_revenue_of_week_by_last_six_month[$value['month']]
                                            ];
                                            continue;
                                        }
                                        foreach($new_revenue_of_week_by_last_six_month['series'] as $key2 => $value2){
                                            if ($value2['id'] != $value['month']) {
                                                $new_revenue_of_week_by_last_six_month['series'][] = [
                                                    'name' => Yii::t('app', 'Amount'),
                                                    'id' => $value['month'],
                                                    'data' => $tmp_revenue_of_week_by_last_six_month[$value['month']]
                                                ];
                                            };
                                        }
                                    }
                                    $new_revenue_by_week['name'] = Yii::t('app', 'Amount');

                                    foreach($revenue_by_last_six_month as $key => $value) {
                                        $new_revenue_by_last_six_month['data'][] = [
                                            'name' => Yii::t('app', 'Month') . ' ' . $value['month'],
                                            'y' => intval($value['Total']),
                                            'drilldown' => $value['month']
                                        ];
                                    }

                                    $new_revenue_by_last_six_month['name'] = Yii::t('app', 'Amount');

                                    echo \miloschuman\highcharts\Highcharts::widget([
                                        'options' => [
                                            'chart' => [
                                                'type' => 'column'
                                            ],
                                            'title' =>  [
                                                'text'=> Yii::t('app', 'Last six month revenue.')
                                            ],
                                            'credits' => [
                                                'enabled' => false
                                            ],
                                            'xAxis' => [
                                                'type' => 'category',

                                            ],
                                            'yAxis'=> [
                                                'min' => 0,
                                                'title'=> [
                                                    'text' => Yii::t('app', 'Money (dong)')
                                                ],
                                                'labels' => [
                                                    'format' => '{value:,.0f}'
                                                ]
                                            ],
                                            'legend' => [
                                                'enabled' => true
                                            ],
                                            'tooltip' => [
                                                'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
                                                'pointFormat' => '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.2f}d</b>',
                                            ],
                                            'plotOptions' => [
                                                'series' => [
                                                    'borderWidth' => 0,
                                                    'dataLabels' => [
                                                        'enabled' => true,
                                                        'format' => '{point.y:,.1f}'
                                                    ]
                                                ]
                                            ],
                                            'series' => [
                                                $new_revenue_by_last_six_month
                                            ],
                                            'drilldown' => $new_revenue_of_week_by_last_six_month
                                        ]
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="well margin-top-10 no-margin no-border">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-6 text-stat">
										<span class="label label-success">
										Revenue: </span>
                                <h3><?= $total_revenue_by_last_six_month['Total']?></h3>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6 text-stat">
										<span class="label label-info">
										Tax: </span>
                                <h3><?=$total_tax_by_last_six_month['Total']?></h3>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6 text-stat">
										<span class="label label-warning">
										Orders: </span>
                                <h3><?=$total_order_by_last_six_month['numberOrder']?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
    <!-- END DASHBOARD FOR SALE-->

    <!-- BEGIN DASHBOARD FOR MANAGE-->
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp"><?= number_format($summary_of_profit[0]['Total Profit'], 2)?><small class="font-green-sharp">&#273;</small></h3>
                        <small>TOTAL PROFIT</small>
                    </div>
                    <div class="icon">
                        <i class="icon-pie-chart"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <h3 class="font-red-haze"><?= number_format($summary_of_orders[0]['Total'], 0)?></h3>
                        <small>TOTAL ORDERS</small>
                    </div>
                    <div class="icon">
                        <i class="icon-like"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp"><?= number_format($summary_of_profit[0]['Total Profit']/$summary_of_orders[0]['Total'], 2)?><small class="font-blue-sharp">&#273;</small></h3>
                        <small>AVERAGE ORDER</small>
                    </div>
                    <div class="icon">
                        <i class="icon-basket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <span class="caption-subject theme-font-color bold uppercase">Sales Summary by Category</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>

                <div class="portlet-body">

                    <div class="row">
                        <div class="col-md-6">

                            <?php
                            $new_summary_sale_by_category_by_years = [];
                            $new_summary_of_sale_by_category = [];
                            $tmp_summary_sale_by_category_by_years = [];
                            $new_summary_of_sale_by_category_percent = [];
                            $summary_category = 0;

                            foreach($summary_sale_by_category_by_years as $key => $value) {
                                if (empty($tmp_summary_sale_by_category_by_years)) {
                                    $tmp_summary_sale_by_category_by_years[$value['name']][] = [$value['year'],intval($value['Total'])];
                                } else if(isset($tmp_summary_sale_by_category_by_years[$value['name']]) &&
                                    $tmp_summary_sale_by_category_by_years[$value['name']] == $value['name']){
                                    $tmp_summary_sale_by_category_by_years[$value['name']][] = [$value['name'], intval($value['Total'])];
                                } else {
                                    $tmp_summary_sale_by_category_by_years[$value['name']][] = [$value['year'], intval($value['Total'])];
                                }
                            }

                            foreach($summary_sale_by_category_by_years as $key => $value) {
                                if (!isset($new_summary_sale_by_category_by_years['series'])) {
                                    $new_summary_sale_by_category_by_years['series'][] = [
                                        'name' => Yii::t('app', 'Amount'),
                                        'id' => $value['name'],
                                        'data' => $tmp_summary_sale_by_category_by_years[$value['name']]
                                    ];
                                    continue;
                                }
                                foreach($new_summary_sale_by_category_by_years['series'] as $key2 => $value2){
                                    if ($value2['id'] != $value['name']) {
                                        $new_summary_sale_by_category_by_years['series'][] = [
                                            'name' => Yii::t('app', 'Amount'),
                                            'id' => $value['name'],
                                            'data' => $tmp_summary_sale_by_category_by_years[$value['name']]
                                        ];
                                    };
                                }
                            }
                            $new_revenue_by_week['name'] = Yii::t('app', 'Amount');

                            foreach($summary_sale_by_category as $key => $value) {
                                $new_summary_of_sale_by_category['data'][] = [
                                    'name' => $value['name'],
                                    'y' => intval($value['Total']),
                                    'drilldown' => $value['name']
                                ];
                                $summary_category += intval($value['Total']);
                            }

                            foreach($summary_sale_by_category as $key => $value) {
                                $new_summary_of_sale_by_category_percent['data'][] = [
                                    'name' => $value['name'],
                                    'y' => intval($value['Total'])/$summary_category*100,
                                ];
                                $summary_category += intval($value['Total']);
                            }

                            $new_summary_of_sale_by_category['name'] = Yii::t('app', 'Amount');

                            $new_summary_of_sale_by_category_percent['colorByPoint'] = true;
                            $new_summary_of_sale_by_category_percent['name'] = Yii::t('app', 'Amount');

                            echo \miloschuman\highcharts\Highcharts::widget([
                                'options' => [
                                    'chart' => [
                                        'type' => 'column'
                                    ],
                                    'title' =>  [
                                        'text'=> Yii::t('app', 'Summary Category Revenue.')
                                    ],
                                    'credits' => [
                                        'enabled' => false
                                    ],
                                    'xAxis' => [
                                        'type' => 'category',

                                    ],
                                    'yAxis'=> [
                                        'min' => 0,
                                        'title'=> [
                                            'text' => Yii::t('app', 'Money (dong)')
                                        ],
                                        'labels' => [
                                            'format' => '{value:,.0f}'
                                        ]
                                    ],
                                    'legend' => [
                                        'enabled' => true
                                    ],
                                    'tooltip' => [
                                        'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
                                        'pointFormat' => '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.2f}d</b>',
                                    ],
                                    'plotOptions' => [
                                        'series' => [
                                            'borderWidth' => 0,
                                            'dataLabels' => [
                                                'enabled' => true,
                                                'format' => '{point.y:,.1f}'
                                            ]
                                        ]
                                    ],
                                    'series' => [
                                        $new_summary_of_sale_by_category
                                    ],
                                    'drilldown' => $new_summary_sale_by_category_by_years
                                ]
                            ]);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            echo \miloschuman\highcharts\Highcharts::widget([
                                'options' => [
                                    'chart' => [
                                        'plotBackgroundColor' => null,
                                        'plotBorderWidth' => null,
                                        'plotShadow' => false,
                                        'type' => 'pie'
                                    ],
                                    'title' => [
                                        'text'=> Yii::t('app', 'Summary Category Revenue.')
                                    ],
                                    'tooltip' => [
                                        'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                                    ],
                                    'plotOptions' => [
                                        'pie' => [
                                            'allowPointSelect' => true,
                                            'cursor' => 'pointer',
                                            'dataLabels' => [
                                                'enabled' => true,
                                                'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                'style' => [
                                                    'color' => new \yii\web\JsExpression('(Highcharts.theme && Highcharts.theme.contrastTextColor)') || 'black'
                                                ]
                                            ]
                                        ]
                                    ],
                                    'series' => [
                                        $new_summary_of_sale_by_category_percent
                                    ]
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <span class="caption-subject theme-font-color bold uppercase"><?= Yii::t('app', 'Sales Summary by Local')?></span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <?php
                        $new_summary_sale_by_location = [];

                        foreach($summary_sale_by_location as $key => $value) {
                            $new_summary_sale_by_location['data'][] = [
                                'name' => $value['district_name'],
                                'y' => intval($value['Total'])
                            ];
                        }
                        $new_summary_sale_by_location['name'] = Yii::t('app', 'Total sales');

                        echo \miloschuman\highcharts\Highcharts::widget([
                            'options' => [
                                'chart' => [
                                    'plotBackgroundColor' => null,
                                    'plotBorderWidth' => null,
                                    'plotShadow' => false,
                                    'type' => 'pie'
                                ],
                                'title' => [
                                    'text' => Yii::t('app', 'Total sale on Ha Noi area')
                                ],
                                'tooltip' => [
                                    'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                                ],
                                'plotOptions' => [
                                    'pie' => [
                                        'allowPointSelect' => true,
                                        'cursor' => 'pointer',
                                        'dataLabels' => [
                                            'enabled' => true,
                                            'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                            'style' => [
                                                'color' => new \yii\web\JsExpression("(Highcharts.theme && Highcharts.theme.contrastTextColor)") || 'black'
                                            ]
                                        ]
                                    ]
                                ],
                                'series' => [
                                    $new_summary_sale_by_location
                                ]
                            ]
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END DASHBOARD FOR MANAGE-->
    <?php } else if (Yii::$app->user->can('/site/marketing')) {?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <span class="caption-subject theme-font-color bold uppercase"><?= Yii::t('app', 'Sales Summary by Local')?></span>
                            <span class="caption-helper"></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <?php
                            $new_summary_sale_by_location = [];

                            foreach($summary_sale_by_location as $key => $value) {
                                $new_summary_sale_by_location['data'][] = [
                                    'name' => $value['district_name'],
                                    'y' => intval($value['Total'])
                                ];
                            }
                            $new_summary_sale_by_location['name'] = Yii::t('app', 'Total sales');

                            echo \miloschuman\highcharts\Highcharts::widget([
                                'options' => [
                                    'chart' => [
                                        'plotBackgroundColor' => null,
                                        'plotBorderWidth' => null,
                                        'plotShadow' => false,
                                        'type' => 'pie'
                                    ],
                                    'title' => [
                                        'text' => Yii::t('app', 'Total sale on Ha Noi area')
                                    ],
                                    'tooltip' => [
                                        'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
                                    ],
                                    'plotOptions' => [
                                        'pie' => [
                                            'allowPointSelect' => true,
                                            'cursor' => 'pointer',
                                            'dataLabels' => [
                                                'enabled' => true,
                                                'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                'style' => [
                                                    'color' => new \yii\web\JsExpression("(Highcharts.theme && Highcharts.theme.contrastTextColor)") || 'black'
                                                ]
                                            ]
                                        ]
                                    ],
                                    'series' => [
                                        $new_summary_sale_by_location
                                    ]
                                ]
                            ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else if (Yii::$app->user->can('/site/admin')) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i><?= $this->title ?>
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse">
                            </a>
                            </a>
                            <a href="javascript:;" class="remove">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <?= \yii\helpers\Html::beginForm('','get') ?>
                        <div class="form-body">
                            <div class="form-group">
                        <pre class="prettyprint linenums"><?php
                            while(($buffer = fgets($file_log)) !== false) {
                                echo $buffer;
                            }
                            fclose($context);
                            ?>
                        </pre>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="col-md-3 btn-set pull-right">
                                <?php echo $this->blocks['submit']; ?>
                            </div>
                        </div>
                        <?= \yii\helpers\Html::endForm() ?>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
        </div>
    <?php } else {?>
        <div class="row">
            <div class="col-md-12">
                <h1><?=Yii::t('app', 'Welcome to Fresh Garden System management')?></h1>
            </div>
        </div>
    <?php } ?>
</div>

<?php
$js = <<<SCRIPT
$('.dropdown-toggle').dropdown();
SCRIPT;
;
$this->registerJs($js);
?>
