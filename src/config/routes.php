<?php

return [
    '/' => [
        'get' => 'IndexController@index',
    ],
    '/users' => [
        'get' => 'UsersController@index',
        'group' => [
            '/{:user}/{:key?}' => [
                'get' => 'UsersController@show',
                'where' => [
                    'user' => '\d+',
                ],
            ],
            '/posts' => [
                'get' => 'PostsController@index'
            ]
        ],
    ],
];