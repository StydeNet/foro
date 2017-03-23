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
    'uses' => 'PostController@show'
])->where('post', '\d+');

Route::get('posts-pendientes', [
    'uses' => 'PostController@index',
    'as' => 'posts.pending'
]);

Route::get('posts-completados', [
    'uses' => 'PostController@index',
    'as' => 'posts.completed'
]);

Route::get('{category?}', [
    'uses' => 'PostController@index',
    'as' => 'posts.index'
]);
