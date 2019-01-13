<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$modules = require __DIR__ . '/modules.php';

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'modules' => $modules,
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfCookie' => false,
        ],
        'response' => [
            'class' => 'yii\web\Response',
//            'format' => 'json',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ( in_array($response->statusCode,[400])) {
                    $response->data = \common\helpers\WeHelper::jsonReturn(null,$response->statusCode);
                    $response->statusCode = 200;
                }
            },
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.0.200;dbname=xiaoshidai',
            'tablePrefix' => 'xsd_',
            'username' => 'dev_xsd',
            'password' => '07fa533360d9',
            'charset' => 'utf8',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error','warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => true,
            'rules' => require(__DIR__ . '/rules.php'),
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'params' => $params,
];