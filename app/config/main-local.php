<?php

$config = [
    'components' => [
        'request' => [
            'cookieValidationKey' => '4kQ8hm0WBL6wkH4dHCOC3mwvr7hfrrSp',
            'enableCsrfValidation' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=xiaoshidai',
            'tablePrefix' => 'xsd_',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']= [
    'class' => yii\gii\Module::className()
    ];
}

return $config;
