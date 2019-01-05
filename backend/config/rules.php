<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'public',
        'pluralize' => false,
        'extraPatterns' => [
            'GET csrftoken' => 'csrftoken',
            'POST,OPTIONS upload/img/<type>' => 'upload-img',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'admin/admin',
        'pluralize' => false,
        'extraPatterns' => [
            'POST,OPTIONS login' => 'login',
            'PUT,OPTIONS change/password' => 'change-password',
            'PUT,OPTIONS  lock/<id>' => 'lock',
            'PUT,OPTIONS  un-lock/<id>' => 'un-lock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/banner' => 'ad/banner'],
        'pluralize' => false,
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/banner/item' => 'ad/banner-item'],
        'pluralize' => false,
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/coupon' => 'coupon/coupon'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,OPTIONS   giveout' => 'giveout',
            'PUT,OPTIONS lock/<id>' => 'lock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/user/coupon' => 'coupon/user-coupon'],
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS lock/<id>' => 'lock',
            'PUT,OPTIONS un-lock/<id>' => 'un-lock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['product/category' => 'goods/category'],
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS  options' => 'options',
            'PUT,OPTIONS show/<id>' => 'show',
            'GET,OPTIONS tree' => 'index-tree',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'users/users',
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS  options' => 'options',
            'GET <id>/lock' => 'lock',
            'GET <id>/unlock' => 'unlock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'area/area',
        'pluralize' => false,
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'goods/goods',
        'pluralize' => false,
        'extraPatterns' =>[
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'goods/item',
        'pluralize' => false,
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['rbac' => 'rbac/rbac'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET permissions'=>'permissions'
        ],
    ],
];