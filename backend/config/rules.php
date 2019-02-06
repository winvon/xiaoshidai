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
        'controller' => ['banner' => 'ad/banner'],
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
            'GET,OPTIONS user' => 'user',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'product/category',
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS show/<id>' => 'show',
            'GET,OPTIONS tree' => 'index-tree',
            'PUT,OPTIONS <id>/sort' => 'sort',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['product/category/release'=>'product/category-release'],
        'pluralize' => false,
        'extraPatterns' => [
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
        'controller' => 'rbac/auth',
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS <id>/lock' => 'lock',//冻结
            'GET,OPTIONS tree' => 'tree',//树
            'GET,OPTIONS checkbox' => 'checkbox',//树
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'rbac/role',
        'pluralize' => false,
        'extraPatterns' => [
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['nav' => 'rbac/nav'],
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS <id>/sort' => 'sort',//排序
            'PUT,OPTIONS <id>/show' => 'show',//显示
            'PUT,OPTIONS <id>/lock' => 'lock',//冻结
            'GET,OPTIONS  menusname' => 'get-menus-name',//父级菜单名称
            'GET,OPTIONS  manage' => 'get-manage-nav',//后台菜单
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'users/role',
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS  options' => 'options',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'users/cert',
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS  options' => 'options',
            'GET,OPTIONS <id>/log' => 'log',//发放日志记录
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'special/topic',
        'pluralize' => false,
        'extraPatterns' => [
            'OPTIONS  options' => 'options',
            'GET,OPTIONS <id>/lock' => 'lock',
            'GET,OPTIONS <id>/unlock' => 'unlock',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['market/active' => 'market_active/marketing-activities'],
        'pluralize' => false,
        'extraPatterns' => [
            'PUT,OPTIONS <id>/lock' => 'lock',
            'PUT,OPTIONS <id>/sort' => 'sort',
            'POST,OPTIONS <id>/users' => 'add-users',//添加用户
            'DELETE,OPTIONS <id>/users/<active_id>' => 'delete-users',//删除用户
            'DELETE,OPTIONS <id>/users/<active_id>' => 'delete-users',//删除用户
        ],
    ],
];