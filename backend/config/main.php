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
    ],
    'params' => $params,
];
