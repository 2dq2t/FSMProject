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
        'js/font-awesome/css/font-awesome.min.css',
        'stylesheet/css_family_vietnamese.css',
        'stylesheet/css_family.css',
        'stylesheet/stylesheet.css',
        'stylesheet/megnor/carousel.css',
        'stylesheet/megnor/custom.css',
        'stylesheet/megnor/bootstrap.min.css',
        'stylesheet/search.css',
        'js/jquery/owl-carousel/owl.carousel.css',
        'js/jquery/owl-carousel/owl.transitions.css',
        'js/jquery/magnific/magnific-popup.css',
        'js/jquery/raty/jquery.raty.css',
    ];
    public $js = [
        'js/megnor/custom.js',
        'js/megnor/jstree.min.js',
        'js/megnor/carousel.min.js',
        'js/megnor/megnor.min.js',
        'js/megnor/jquery.custom.min.js',
        'js/megnor/scrolltop.min.js',
        'js/megnor/jquery.formalize.min.js',
        'js/jquery/magnific/jquery.magnific-popup.min.js',
        'js/jquery/raty/jquery.raty.js',
        'js/handlebars.js',
        'js/typeahead.bundle.min.js',
        'js/search.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
