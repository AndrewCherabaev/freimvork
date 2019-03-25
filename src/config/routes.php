<?php

return [
    '/' => [
        'action' => 'IndexController@index',
    ],
    '/users' => [
        'action' => 'UsersController@index',
        'group' => [
            '/{:id}/{:key?}' => [
                'action' => 'UsersController@show',
                'patterns' => [
                    'id' => '\d+',
                ],
            ],
            '/posts' => [
                'action' => 'PostsController@index'
            ]
        ],
    ],
];