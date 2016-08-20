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

Route::get('/', function() {
	return view('index');
});

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/resetpassword', 'AuthenticateController@resetPassword');
Route::get('authenticate/{remember_token}','AuthenticateController@noPassword');


// where is this being used?
Route::get('background_image', 'BackgroundImageController@index');

// rename api/file
Route::resource('file', 'FileController');

// rename api/setting
Route::resource('setting', 'SettingController');

// merge two of these
Route::get('/settings', function(){
	
	$user = null;
	
	if ($token = JWTAuth::getToken()) {
		$user = JWTAuth::parseToken()->authenticate();
    }
	return Setting::all();
});


Route::group(array('prefix' => 'api'), function(){				

	if ($token = JWTAuth::getToken()) {

		$user = JWTAuth::parseToken()->authenticate();

    }

	Route::resource('comment', 'CommentController');
	Route::resource('community', 'CommunityController');
	Route::resource('delegation', 'DelegationController');
	Route::resource('department', 'DepartmentController');
	Route::resource('file', 'FileController');
	Route::resource('ethnic_origin', 'EthnicOriginController');
	Route::resource('motion', 'MotionController');
	Route::resource('motion.comment','MotionCommentController', ['only'=>['index']]);
	Route::resource('motion.motionfile','MotionFileController');
	Route::resource('motion.vote','MotionVoteController', ['only'=>['index']]);
	Route::resource('page', 'PageController');
	Route::resource('setting', 'SettingController');
	Route::resource('user', 'UserController');


	Route::group(['middleware' => 'jwt.auth'], function(){


		Route::resource('background_image', 'BackgroundImageController');

		Route::get('comment/{id}/restore','CommentController@restore');
		Route::resource('comment_vote', 'CommentVoteController');

		Route::get('motion/{id}/restore','MotionController@restore');
		Route::post('motionfile/flowUpload', 'MotionFileController@flowUpload');

		Route::resource('role', 'RoleController');
		
		Route::resource('user.vote', 'UserVoteController'); //, ['only'=>['index']]);
		Route::resource('user.comment', 'UserCommentController'); //, ['only'=>['index']]);
		Route::resource('user.role', 'UserRoleController'); 

		Route::resource('vote', 'VoteController');


	});
});
