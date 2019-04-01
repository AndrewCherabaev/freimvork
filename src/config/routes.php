<?php

return [
    '/' => [
        'get' => 'IndexController@index',
    ],
    '/users' => [
        'get' => 'UsersController@index',
        'group' => [
            '/{:id}/{:key?}' => [
                'get' => 'UsersController@show',
                'patterns' => [
                    'id' => '\d+',
                ],
            ],
            '/posts' => [
                'get' => 'PostsController@index'
            ]
        ],
    ],
];