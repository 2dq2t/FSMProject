<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
		'mediacenter/assets/css/bootstrap.min.css',
		'mediacenter/assets/css/main.css',
	    'mediacenter/assets/css/green.css',
	    'mediacenter/assets/css/owl.carousel.css',
		'mediacenter/assets/css/owl.transitions.css',
		'mediacenter/assets/css/animate.min.css',
		'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800',
		'mediacenter/assets/css/font-awesome.min.css',
		'mediacenter/assets/images/favicon.ico',
    ];
    public $js = [
		'mediacenter/assets/js/jquery-migrate-1.2.1.js',
		'mediacenter/assets/js/bootstrap.min.js',
		'http://maps.google.com/maps/api/js?sensor=false&amp;language=en',
		'mediacenter/assets/js/gmap3.min.js',
		'mediacenter/assets/js/bootstrap-hover-dropdown.min.js',
		'mediacenter/assets/js/owl.carousel.min.js',
		'mediacenter/assets/js/css_browser_selector.min.js',
		'mediacenter/assets/js/echo.min.js',
		'mediacenter/assets/js/jquery.easing-1.3.min.js',
		'mediacenter/assets/js/bootstrap-slider.min.js',
		'mediacenter/assets/js/jquery.raty.min.js',
		'mediacenter/assets/js/jquery.prettyPhoto.min.js',
		'mediacenter/assets/js/jquery.customSelect.min.js',
		'mediacenter/assets/js/wow.min.js',
		'mediacenter/assets/js/scripts.js',
		'http://w.sharethis.com/button/buttons.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
