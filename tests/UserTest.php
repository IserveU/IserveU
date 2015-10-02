<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class UserTest  {

	protected $baseUrl = 'http://192.168.10.10';	// sets base URL, change this to whatever local host you
													// are working on
	use WithoutMiddleware;	//removes token auth 
	use DatabaseTransactions;

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */

	public function testMakeAdmin(){
			//Login
    		$user = factory('App\User')->make(); 
    		$this->actingAs($user);
    		//dd($user);

			//Auth::loginUsingId(2);
			$commentvote = ['position' => 1, 'comment_id' => 3];
			$response = $this->call('POST', '/api/comment_vote', $commentvote);
			
			if($this->response->getStatusCode() != 200) echo $this->htmlResponseToConsole($response,"testCommentVote");
			
			return $this->assertResponseOk(); //checks status code, expects 200
	}







}