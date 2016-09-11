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
})->name('home');

Validator::extend('notRequired', 'CustomValidation@notRequired');

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/resetpassword', 'AuthenticateController@resetPassword');
Route::get('authenticate/{remember_token}','AuthenticateController@noPassword');


Route::group(['prefix' => 'api'], function(){				
	
	//User
	Route::resource('user', 'User\UserController');
	Route::resource('user.vote', 'User\UserVoteController',['only'=>['index']]); 
	Route::resource('user.comment', 'User\UserCommentController',['only'=>['index']]); 
	Route::resource('user.role', 'User\UserRoleController',['only'=>['index','store','update','destroy']]); 

	//Vote
	Route::resource('vote', 'Vote\VoteController', ['only'=>['index','show','update','destroy']]);
	Route::resource('vote/{vote}/comment','Vote\VoteCommentController', ['only'=>['store']]);

	//Comment
	Route::get('comment/{id}/restore','Comment\CommentController@restore'); //Could add deleted status and update it
	Route::resource('comment', 'Comment\CommentController',['except'=>['store']]);
	Route::resource('comment/{comment}/comment_vote','Comment\CommentCommentVoteController',['only'=>['store']]);

	//Comment Vote
	Route::resource('comment_vote', 'CommentVote\CommentVoteController',['except'=>['store']]);

	//Motion
	Route::resource('motion/{motion}/vote','Motion\MotionVoteController', ['only'=>['index','store']]);
	Route::resource('motion/{motion}/comment','Motion\MotionCommentController',['only'=>['index']]);
	Route::get('motion/{id}/restore','Motion\MotionController@restore');
	//Route::resource('motion.motionfile','Motion\MotionFileController'); //Should be updated in file rework to share same
	//Route::post('motionfile/flowUpload', 'Motion\MotionFileController@flowUpload');  //Should be updated in file rework to share same
	Route::resource('motion', 'Motion\MotionController');

	//Page
	Route::resource('page', 'PageController');

	//Role
	Route::resource('role', 'RoleController',['only'=>['index']]); //What is this for?

	//Setting	
	Route::resource('setting', 'SettingController',['only'=>['index','update']]);

	//Administrator only
	Route::group(['middleware' => ['role:administrator']], function(){

		//Community
		Route::resource('community', 'CommunityController');

		//Department
		Route::resource('department', 'DepartmentController');

		//Ethnic Origin
		Route::resource('ethnic_origin', 'EthnicOriginController');

		//File
		//Route::resource('file', 'FileController');

		//Background Image
		Route::resource('background_image', 'BackgroundImageController');
	});



});

