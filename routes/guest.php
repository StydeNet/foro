<?php

Route::get('register', [
    'uses' => 'RegisterController@create',
    'as' => 'register'
]);

Route::post('register', [
    'uses' => 'RegisterController@store',
]);

Route::get('login', [
    'uses' => 'TokenController@create',
    'as' => 'token',
]);

Route::post('login', [
    'uses' => 'TokenController@store',
]);

Route::get('login/{token}', [
    'uses' => 'LoginController@login',
    'as' => 'login',
]);
