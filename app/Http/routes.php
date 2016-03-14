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


Route::resource('delegation', 'DelegationController');

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/resetpassword', 'AuthenticateController@resetPassword');

Route::get('authenticate/{remember_token}','AuthenticateController@noPassword');

Route::get('background_image', 'BackgroundImageController@index');

Route::resource('file', 'FileController');

// access across site because users need it to see what's going on, maybe?? 
Route::resource('setting', 'SettingController');

Route::get('/settings', function(){
	
	$user = null;
	
	if ($token = JWTAuth::getToken()) {
		$user = JWTAuth::parseToken()->authenticate();
    }
	return Setting::all();
});


Route::get('/', function() {
	return view('index');
});


Route::group(array('prefix' => 'api'), function(){

		Route::resource('user', 'UserController');
		Route::resource('ethnic_origin', 'EthnicOriginController');
		
		if ($token = JWTAuth::getToken()) {
			$user = JWTAuth::parseToken()->authenticate();
	    }

		Route::resource('comment', 'CommentController');
		Route::resource('community', 'CommunityController');
		Route::resource('department', 'DepartmentController');
		Route::resource('motion', 'MotionController');
		Route::resource('motion.comment','MotionCommentController', ['only'=>['index']]);
		Route::resource('motion.motionfile','MotionFileController');
		Route::resource('motion.section','Motion\MotionSectionController');
		Route::resource('motion.vote','MotionVoteController', ['only'=>['index']]);
		Route::resource('page', 'PageController');


	Route::group(['middleware' => 'jwt.auth'], function(){


		Route::resource('role', 'RoleController');


		Route::resource('background_image', 'BackgroundImageController');

		Route::get('motion/{id}/restore','MotionController@restore');
		Route::post('motionfile/flowUpload', 'MotionFileController@flowUpload');

		Route::resource('vote', 'VoteController');


		Route::get('comment/{id}/restore','CommentController@restore');

		Route::resource('comment_vote', 'CommentVoteController');
		
		Route::resource('user.vote', 'UserVoteController'); //, ['only'=>['index']]);
		Route::resource('user.comment', 'UserCommentController'); //, ['only'=>['index']]);
		Route::resource('user.role', 'UserRoleController'); 
	
	});
});
