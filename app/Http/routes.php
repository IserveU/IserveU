<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::resource('test','TestController');


Route::get('users/rules','UserController@rules');
Route::resource('users', 'UserController');

Route::get('motions/rules','MotionController@rules');
Route::resource('motions', 'MotionController');

Route::get('/', function()
{
	return view('index');
});

Route::group(array('prefix' => 'api'), function()
{
	Route::resource('motion', 'MotionController');
	Route::resource('user', 'UserController');
});


//Entrust::routeNeedsPermission('users/edit');
