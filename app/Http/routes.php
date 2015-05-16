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

Route::get('/', function()
{
	return view('index');
});

Route::group(array('prefix' => 'api'), function()
{

	Route::get('motion/getcomments/{motionid}','MotionController@getComments');
	Route::get('motion/rules','MotionController@rules');
	Route::resource('motion', 'MotionController');

	Route::get('event/rules','EventController@rules');
	Route::resource('event', 'EventController');

	Route::get('user/loggedin', 'UserController@checkLogin');
	Route::get('user/rules','UserController@rules');
	Route::resource('user', 'UserController');
	
	Route::get('vote/rules','VoteController@rules');
	Route::resource('vote', 'VoteController');

	Route::get('comment/rules','CommentController@rules');
	Route::resource('comment', 'CommentController');
});
