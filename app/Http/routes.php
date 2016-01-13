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

Route::get('/settings', function(){
	$user = null;
	if ($token = JWTAuth::getToken()) {
		$user = JWTAuth::parseToken()->authenticate();
    }
	return array('themename'=>Setting::get('themename','default'),'background_image'=>(new BackgroundImage)->today(),'user'=>$user);
});

Route::get('/', function() {
	return view('index');
});



Route::group(array('prefix' => 'api'), function(){


	Route::resource('user', 'UserController');


	Route::resource('ethnic_origin', 'EthnicOriginController');

	Route::group(['middleware' => 'jwt.auth'], function(){

		Route::resource('setting', 'SettingController');

		Route::resource('role', 'RoleController');
				
		Route::resource('background_image', 'BackgroundImageController');

		Route::get('motion/{id}/restore','MotionController@restore');
		Route::resource('motion', 'MotionController');
		Route::resource('motion.comment','MotionCommentController', ['only'=>['index']]);
		Route::resource('motion.motionfile','MotionFileController');
		Route::resource('motion.vote','MotionVoteController', ['only'=>['index']]);

		Route::resource('department', 'DepartmentController');

		Route::resource('vote', 'VoteController');

		Route::get('comment/{id}/restore','CommentController@restore');
		Route::resource('comment', 'CommentController');

		Route::resource('comment_vote', 'CommentVoteController');
		
		Route::resource('user.vote', 'UserVoteController'); //, ['only'=>['index']]);
		Route::resource('user.comment', 'UserCommentController'); //, ['only'=>['index']]);
		Route::resource('user.role', 'UserRoleController'); 

	});
});
