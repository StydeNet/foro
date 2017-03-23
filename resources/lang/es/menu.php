<?php

return [

    'main' => [
        'list' => [
            'title' => 'Ver posts',
            'route' => 'posts.index',
        ],
        'create' => [
            'title' => 'Crear post',
            'route' => 'posts.create',
        ],
    ],

    'filters' => [
        'all' => [
            'title' => 'Posts',
            'route' => 'posts.index',
        ],
        'pending' => [
            'title' => 'Posts pendientes',
            'route' => 'posts.pending',
        ],
        'completed' => [
            'title' => 'Posts completados',
            'route' => 'posts.completed',
        ],
    ],

];
