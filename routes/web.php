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

Route::get('/', 'HomeController@index');

Route::post('authenticate', 'AuthenticateController@authenticate')->name('login');
Route::post('authenticate/resetpassword', 'AuthenticateController@resetPassword')->name('reset.trigger');
Route::get('authenticate/{token}', 'AuthenticateController@noPassword')->name('reset.return');

Route::group(['prefix' => 'api'], function () {

    //User
    Route::post('user/{user}/setpreference/{key}', 'User\UserController@setPreference');
    Route::resource('user', 'User\UserController', ['except' => ['create', 'edit']]);
    Route::resource('user/{user}/file', 'FileController', ['except' => ['create', 'edit', 'index']]);

    Route::resource('user.vote', 'User\UserVoteController', ['only' => ['index']]);
    Route::resource('user.comment', 'User\UserCommentController', ['only' => ['index']]);
    Route::resource('user.comment_vote', 'User\UserCommentVoteController', ['only' => ['index']]);
    Route::resource('user.role', 'User\UserRoleController', ['only' => ['index', 'store', 'update', 'destroy']]);

    //Vote
    Route::resource('vote', 'Vote\VoteController', ['only' => ['index', 'show', 'update', 'destroy']]);
    Route::resource('vote/{vote}/comment', 'Vote\VoteCommentController', ['only' => ['store']]);

    //Comment
    Route::resource('comment', 'Comment\CommentController', ['except' => ['store', 'create', 'edit']]);
    Route::resource('comment/{comment}/comment_vote', 'Comment\CommentCommentVoteController', ['only' => ['store']]);

    //Comment Vote
    Route::resource('comment_vote', 'CommentVote\CommentVoteController', ['except' => ['store', 'create', 'edit']]);

    //Motion
    Route::resource('motion/{motion}/vote', 'Motion\MotionVoteController', ['only' => ['index', 'store']]);
    Route::resource('motion/{motion}/comment', 'Motion\MotionCommentController', ['only' => ['index']]);
    Route::get('motion/{id}/restore', 'Motion\MotionController@restore');

    \App\File::routes('motion');
    Route::resource('motion', 'Motion\MotionController', ['except' => ['create', 'edit']]);

    //Page
    \App\File::routes('page');
    Route::resource('page', 'PageController', ['except' => ['create', 'edit']]);

    //Role
    Route::resource('role', 'RoleController', ['only' => ['index']]); //What is this for?

    //Setting
    Route::resource('setting', 'SettingController', ['only' => ['index', 'update']]);

    //Community
    Route::resource('community', 'CommunityController');

    //Department
    Route::resource('department', 'DepartmentController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

    //Administrator only
    Route::group(['middleware' => ['role:administrator']], function () {

        //Ethnic Origin
        Route::resource('ethnic_origin', 'EthnicOriginController', ['only' => ['index']]);
    });
});
