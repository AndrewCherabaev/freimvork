<?php

return [
    '/' => [
        'action' => 'IndexController@index',
    ],
    '/users' => [
        'action' => 'UsersController@index',
    ],
    '/users/{:id}/{:key?}' => [
        'action' => 'UsersController@show',
        'patterns' => [
            'id' => '\d+',
        ],
    ],
];