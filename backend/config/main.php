<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'controllerMap' => [
        'assignment' => 'backend\controllers\AssignmentController',
        'backup' => 'backend\controllers\BackupController',
        'category' => 'backend\controllers\CategoryController',
        'customer' => 'backend\controllers\CustomerController',
        'employee' => 'backend\controllers\EmployeeController',
        'faq' => 'backend\controllers\FaqController',
        'guest' => 'backend\controllers\GuestController',
        'i18n' => 'backend\controllers\I18nController',
        'log' => 'backend\controllers\LogController',
        'offer' => 'backend\controllers\OfferController',
        'order' => 'backend\controllers\OrderController',
        'orderstatus' => 'backend\controllers\OrderStatusController',
        'permission' => 'backend\controllers\PermissionController',
        'product' => 'backend\controllers\ProductController',
        'rating' => 'backend\controllers\RatingController',
        'role' => 'backend\controllers\RoleController',
        'route' => 'backend\controllers\RouteController',
        'season' => 'backend\controllers\SeasonController',
        'site' => 'backend\controllers\SiteController',
        'slideshow' => 'backend\controllers\SlideShowController',
        'tag' => 'backend\controllers\TagController',
        'unit' => 'backend\controllers\UnitController',
        'voucher' => 'backend\controllers\VoucherController'
    ],
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvgrid/messages',
                'forceTranslation' => true
            ]
        ],
        'markdown' => [
            'class' => 'kartik\markdown\Module',
            // array the the internalization configuration for this module
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@markdown/messages',
                'forceTranslation' => true
            ]
        ],
        'backuprestore' => [
            'class' => '\oe\modules\backuprestore\Module', //'layout' => '@admin-views/layouts/main', or what ever layout you use ... ...
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\Employee',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_backendUser', // unique for backend
            ]
        ],
        'session' => [
            'name' => 'PHPBACKSESSID',
            'savePath' => sys_get_temp_dir(),
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '[DIFFERENT UNIQUE KEY]',
            'csrfParam' => '_backendCSRF',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/metronic/views'
                ],
                'baseUrl' => '@backend/themes/metronic',
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest']
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d/m/Y'
        ],
    ],
    'params' => $params,
];
