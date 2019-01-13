<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'public',
        'pluralize' => false,
        'extraPatterns' => [
            'GET test' => 'test',
            'GET csrftoken' => 'csrftoken',
            'POST,OPTIONS upload/img/<type>' => 'upload-img',
            'POST,OPTIONS upload' => 'upload',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['emp' => 'emp/emp'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,OPTIONS login' => 'login',
            'PUT,OPTIONS change/password' => 'change-password',
            'PUT,OPTIONS  lock/<id>' => 'lock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['ad' => 'ad/ad'],
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS  <id>/show' => 'show',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['banner'=>'ad/banner'],
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS  <id>/lock' => 'lock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['coupon' => 'coupon/coupon'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,OPTIONS   giveout' => 'giveout',
            'PUT,OPTIONS lock/<id>' => 'lock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['user/coupon' => 'coupon/user-coupon'],
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS lock/<id>' => 'lock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'product/category',
        'pluralize' => false,
        'extraPatterns' => [
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
            'GET,OPTIONS <id>/lock' => 'lock',
            'GET,OPTIONS <id>/unlock' => 'unlock',
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
        'controller' => 'product/product',
        'pluralize' => false,
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'product/chapters',
        'pluralize' => false,
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['rbac' => 'rbac/rbac'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET permissions' => 'permissions',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['menu' => 'rbac/menu'],
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS <id>/sort' => 'sort',//排序
            'PUT,OPTIONS <id>/show' => 'show',//显示
            'GET,OPTIONS  menusname' => 'get-menus-name',//父级菜单名称
        ],
    ],
];