<html lang="vi">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8">
    <title><?= Yii::t('app', 'Invoice') ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!--    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">-->
    <link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="metronic/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/admin/css/layout.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="metronic/assets/admin/css/themes/light.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/admin/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/assets/pages/css/prettify.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <script src="metronic/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<?php
    ?>
    <body class="page-header-fixed page-sidebar-closed-hide-logo">
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <!-- BEGIN PAGE CONTENT-->
                <div class="row">
                    <div class="col-md-12">

                    </div>
                </div>
                <!-- END PAGE CONTENT-->
            </div>
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
</body>

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>

<script src="metronic/assets/global/plugins/respond.min.js"></script>
<script src="metronic/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<!--    <script src="metronic/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>-->
<script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!--    <link href="http://www.wprunner.com/wp-content/themes/wprunner-v2/assets/css/prettify.css" rel="stylesheet">-->
<script src="metronic/assets/pages/scripts/prettify.js"></script>
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/scripts/layout.js" type="text/javascript"></script>
<!--<script src="metronic/assets/admin/scripts/demo.js" type="text/javascript"></script>-->
<script type="text/javascript">
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        Login.init();
    });
</script>
<!-- END JAVASCRIPTS -->
</html>