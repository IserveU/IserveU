<?php

use App\BackgroundImage;

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


Route::get('comment/approve/{id}/{code}', 'CommentController@approve');

Route::get('comment/delete/{id}/{code}', 'CommentController@approve');

Route::post('authenticate', 'AuthenticateController@authenticate');


Route::get('/settings', function(){
	return array('themename'=>config('app.themename'),'background_image'=>(new BackgroundImage)->today());
});


Route::get('/', function() {
	return view('index');
});



Route::group(array('prefix' => 'api'), function(){


	Route::post('user/grantpermission', 'UserController@grantPermission');
	Route::get('user/authenticateduser', 'UserController@authenticatedUser');
	Route::resource('user', 'UserController');


	Route::resource('ethnic_origin', 'EthnicOriginController');

	Route::group(['middleware' => 'jwt.auth'], function(){


		Route::resource('background_image', 'BackgroundImageController');
		// Route::get('motion/getcomments/{motionid}','MotionController@getComments');
		Route::resource('motion', 'MotionController');
		Route::resource('motion.comment', 'MotionCommentController', ['only'=>['index']]);


		Route::resource('department', 'DepartmentController');

		Route::resource('vote', 'VoteController');

		Route::get('comment/{id}/restore','CommentController@restore');
		Route::resource('comment', 'CommentController');

		Route::resource('comment_vote', 'CommentVoteController');

		Route::resource('user.vote', 'UserVoteController', ['only'=>['index']]);


	});
});
