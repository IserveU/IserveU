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

Route::resource('test','TestController');



Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::get('users/rules','UserController@rules');
Route::resource('users', 'UserController');




Route::resource('blocks', 'BlockController');

Route::get('/', function()
{
	return view('index');
});

Route::group(array('prefix' => 'api'), function()
{
	Route::resource('motion', 'MotionController');
	Route::resource('user', 'UserController');
});