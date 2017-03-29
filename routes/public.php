<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/home', 'HomeController@index');

Route::get('posts/{post}-{slug}', [
    'as' => 'posts.show',
    'uses' => 'ShowPostController'
])->where('post', '\d+');

Route::get('posts-pendientes/{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.pending'
]);

Route::get('posts-completados/{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.completed'
]);

Route::get('{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.index'
]);
