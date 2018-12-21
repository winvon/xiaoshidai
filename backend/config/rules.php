<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'admin/admin',
        'pluralize' => false,
        'extraPatterns' => [
            'POST login' => 'login',
            'PUT,PATCH lock/<id>' => 'lock',
            'PUT,PATCH un-lock/<id>' => 'un-lock',
        ],
    ],
];