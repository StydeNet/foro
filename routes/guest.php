<?php

Route::get('register', [
    'uses' => 'RegisterController@create',
    'as' => 'register'
]);

Route::post('register', [
    'uses' => 'RegisterController@store',
]);

Route::get('confirmation', [
	'uses' => 'RegisterController@confirmationMessage',
	'as' => 'register_confirmation'
]);
