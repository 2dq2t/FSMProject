<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'EHOTCcNGC4HCKHyhJ8KF44izLVOqDkvA',
        ],
        'CommonFunction'=>[
            'class'=>'frontend\components\CommonFunction',
        ],
        'ProductBestSeller'=>[
            'class'=>'frontend\components\ProductBestSeller',
        ],
        'Category'=>[
            'class'=>'frontend\components\Category',
        ],
        'SpecialProduct'=>[
            'class'=>'frontend\components\SpecialProduct',
        ],
        'Header'=>[
            'class'=>'frontend\components\Header',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
