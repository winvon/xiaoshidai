<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/test/test',
        'pluralize' => false,
        'extraPatterns' => [
            'GET method'=>'method'
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/site'=>'v1/site/site'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET category/release'=>'category-release',
            'GET banners'=>'banners',
            'GET nav'=>'nav',
        ],
    ],
];