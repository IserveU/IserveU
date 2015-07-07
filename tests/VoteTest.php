<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;


/* To run all tests, execute "phpunit" in base directory */

class IkeTest extends TestCase {

	protected $baseUrl = 'http://192.168.10.10';	// sets base URL, change this to whatever local host you
													// are working on
	use WithoutMiddleware;	//removes token auth 

	use DatabaseTransactions;

	public $user;


	
    




    //		



	   public function __construct() {
	  //    $this->user = 
	//     
	   }


	
	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */

	/* this tests the users vote, logins as ike, votes on motion 1, and beccause Ike has alreayd voted it 
	returns an error code 403 and goes into a function that posts an update for thumbs down, because of
	event handler it might not be working .... if we get Mockery working on this we can run "$this->withoutEvents(); 
	just to check the API*/

// 	public function testPostVote(){
// 			$this->post('/authenticate', ['email' => 'saunders.ike@gmail.com', 'password' => 'abcd1234']);
// 			Auth::loginUsingId(2);
// 			$vote = ['position' => 1, 'motion_id' => 1];
// 			$response = $this->call('POST', '/api/vote', $vote);

// 			if($this->response->getStatusCode() != 403) { //What does this do? Everything seems go go into this anyway
// 					$this->post('/authenticate', ['email' => 'saunders.ike@gmail.com', 'password' => 'abcd1234']);
// 					$vote = ['position' => 0];
// 				  	$updateresponse = $this->call('PUT', '/api/vote/1', $vote); //Don't reuse variables here, it's confusing to try following them
// 				  	if($updateresponse->getStatusCode() != 200){
// 					echo "here";
// 				  	//this code parses the return response to give a better error message
// 					//else it will just return the status code
// 					$thisdatais = "\n\033[31m testUpdateVoteResponseForAbstaining: "; // useCamelCaseForReadibility
// 			  		$pos = strpos($updateresponse, 'exception_message">');
// 					$data = substr($updateresponse, $pos, ( strpos($updateresponse, PHP_EOL, $pos) ) - $pos);
// 					echo $thisdatais;
// 			  		echo $data;			
// 			  		$posOfStackTrace = strpos($updateresponse, '<ol class="traces list_exception">');
// 					$dataOfStackTrace = substr($updateresponse, $posOfStackTrace, ( strpos($updateresponse, '</li>', $posOfStackTrace) ) - $posOfStackTrace);	
// 					echo $dataOfStackTrace;

// 			  		$updateresponse->assertResponseOk();
// 			  	}
// 		  	}
// 		}

// 	/*this tests the user to post a comment, should exist after having made the vote, as the app
// 	has not been torn down on Ike's account*/ 

// 	public function testPostComment(){

// 		$this->post('/authenticate', ['email' => 'saunders.ike@gmail.com', 'password' => 'abcd1234']);
// 		Auth::loginUsingId(2);
// 		$comment = ['motion_id' => 2, 'approved' =>0, 'text' => 'this is text']; // This requires vote_id not motion ID. You have to vote before you can comment.
// 		$response = $this->call('POST', 'api/comment', $comment);	
// 		if($this->response->getStatusCode() != 200){
// 			//echo $response;
// 			//posts cto comment api
// /*
// 			$thisdatais = "\n\033[31m testPostCommentResponse: ";
// 	  		$pos = strpos($response, '<span class="exception_message">');
// 	  		$pos = $pos + 32;
// 			$data = substr($response, $pos, ( strpos($response, '</span>', $pos) ) - $pos); //JESSICA Can you get the message and the first line of the stack trace so I can see what line produced the error?
// 			echo $thisdatais;
// 	  		echo $data;
// 			$posOfStackTrace = strpos($response, '<ol class="traces list_exception">');
// 			$posOfStackTrace = $posOfStackTrace + 32;
// 			$dataOfStackTrace = substr($response, $posOfStackTrace, ( strpos($response, '</li>', $posOfStackTrace) ) - $posOfStackTrace);	
// 			echo $dataOfStackTrace;
// */

// 	  		$this->assertResponseOk(); //checks status code, expects 200
// 	  		//die();
// 	  	}
// 	}

	/*this tests the comment vote on Ike's account, by default he should have not already voted on */

	public function testCommentVote(){
			//Login
			$this->user = factory('App\User')->make();
			$this->actingAs($this->user);
    		
    		dd($this->user);

			//Auth::loginUsingId(2);
			$commentvote = ['position' => 1, 'comment_id' => 3];
			$response = $this->call('POST', '/api/comment_vote', $commentvote);
			
			if($this->response->getStatusCode() != 200) echo $this->htmlResponseToConsole($response,"testCommentVote");
			
			return $this->assertResponseOk(); //checks status code, expects 200
	}

	public function testBS(){
		dd($this->user);

	}


	// public function testUpdateCommentVote(){


	// 		$this->post('/authenticate', ['email' => 'saunders.ike@gmail.com', 'password' => 'abcd1234']);
	// 		Auth::loginUsingId(2);
	// 		$commentvote = ['position' => -1, 'comment_vote_id' => 1];
	// 		$response = $this->call('PUT', '/api/comment_vote/1', $commentvote);
	// 		if($this->response->getStatusCode() != 200){
	// 			//$thisdatais = "\n\033[31m testUpdateCommentVoteResponse: ";
	// 			//	$pos = strpos($response, '<span class="exception_message">');
	// 			//$data = substr($response, $pos, ( strpos($response, PHP_EOL, $pos) ) - $pos);

	// 			//echo HtmlResponseToConsole($response);

	// 			// echo $thisdatais;
	// 			// echo $data;
	// 			// $posOfStackTrace = strpos($response, '<ol class="traces list_exception">');
	// 			// $dataOfStackTrace = substr($response, $posOfStackTrace, ( strpos($response, '</li>', $posOfStackTrace) ) - $posOfStackTrace);	
	// 			// echo $dataOfStackTrace;

	// 			$this->assertResponseOk(); //checks status code, expects 200
	// 		}
	// 		$this->seeInDatabase('comment_votes', ['id' => 1, 'position' => -1]); //checks to see if comment changed in database
	// }


	// /*creates a new user with basic fields filled in*/

	// public function testCreatesNewUser(){
	// 	$user = [
	// 	'email' 			=>	'example@website.ca',
 //        'password'			=>	'abcd1234',
 //        'first_name'		=>	'Joe',
 //        'middle_name'		=>	'Red',
 //        'last_name'			=>	'Truck',
 //        'ethnic_origin_id'	=>	null,
 //        'date_of_birth'		=>	null,
 //        'public'			=>	0,

 //       	];

	// 	$this->call('POST', '/api/user', $user);
	// 	$this->post('/authenticate', ['email' => 'example@website.ca', 'password' => 'abcd1234']);
		
	// 	$this->get('/settings');
	// 	$arrayOfSettings = json_decode($this->response->getContent());
	// 	$id = $arrayOfSettings->user->id;
	// 	Auth::loginUsingId($id);
	// 	DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => 4));
		

	// }


	// /* runs same as Ike's */

	// public function testNewUserVote(){

	// 	$user = [
	// 	'email' 			=>	'example@website.ca',
 //        'password'			=>	'abcd1234',
 //        'first_name'		=>	'Joe',
 //        'middle_name'		=>	'Red',
 //        'last_name'			=>	'Truck',
 //        'ethnic_origin_id'	=>	null,
 //        'date_of_birth'		=>	null,
 //        'public'			=>	0,

 //       	];

	// 	$this->call('POST', '/api/user', $user);
	// 	$this->post('/authenticate', ['email' => 'example@website.ca', 'password' => 'abcd1234']);
		
	// 	$this->get('/settings');
	// 	$arrayOfSettings = json_decode($this->response->getContent());
	// 	$id = $arrayOfSettings->user->id;
	// 	Auth::loginUsingId($id);
	// 	DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => 4));
		

	// 	$vote = ['position' => -1, 'motion_id' => 1];
	// 	$response = $this->call('POST', '/api/vote', $vote);
	// 	if($this->response->getStatusCode() != 200){ // if not on success it will enter this function
	// 		 //posts the vote to the api
	// 		//this code parses the return response to give a better error message
	// 		//else it will just return the status code
	// 		//echo $response;
	// 		$thisdatais = "\n\033[31m testNewUserVoteResponse: \033";
	//   		$pos = strpos($response, '<span class="exception_message">');
	// 		$data = substr($response, $pos, ( strpos($response, PHP_EOL, $pos) ) - $pos);
	// 		echo $thisdatais;
	//   		echo $data;
	//   		$posOfStackTrace = strpos($response, '<ol class="traces list_exception">');
	// 		$dataOfStackTrace = substr($response, $posOfStackTrace, ( strpos($response, '</li>', $posOfStackTrace) ) - $posOfStackTrace);	
	// 		echo $dataOfStackTrace;


	//   		$this->assertResponseOk();	//this will return the status code in the console
	//   	}
	// }

	// public function testNewUserPostComment(){
		
	// 		$user = [
	// 	'email' 			=>	'example@website.ca',
 //        'password'			=>	'abcd1234',
 //        'first_name'		=>	'Joe',
 //        'middle_name'		=>	'Red',
 //        'last_name'			=>	'Truck',
 //        'ethnic_origin_id'	=>	null,
 //        'date_of_birth'		=>	null,
 //        'public'			=>	0,

 //       	];

	// 	$this->call('POST', '/api/user', $user);
	// 	$this->post('/authenticate', ['email' => 'example@website.ca', 'password' => 'abcd1234']);
		
	// 	$this->get('/settings');
	// 	$arrayOfSettings = json_decode($this->response->getContent());
	// 	$id = $arrayOfSettings->user->id;
	// 	Auth::loginUsingId($id);
	// 	DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => 4));
		

	// 	$comment = ['motion_id' => 2, 'approved' =>0, 'text' => 'this is text'];
	// 	$response = $this->call('POST', 'api/comment', $comment);
	// 	if($this->response->getStatusCode() != 200){
			
	// 		$thisdatais = "\n\033[31m testNewUserPostCommentResponse: \033";
	//   		$pos = strpos($response, '<span class="exception_message">');
	// 		$data = substr($response, $pos, ( strpos($response, PHP_EOL, $pos) ) - $pos);
	// 		echo $thisdatais;
	//   		echo $data;
	//   		$posOfStackTrace = strpos($response, '<ol class="traces list_exception">');
	// 		$dataOfStackTrace = substr($response, $posOfStackTrace, ( strpos($response, '</li>', $posOfStackTrace) ) - $posOfStackTrace);	
	// 		echo $dataOfStackTrace;

	//   		$this->assertResponseOk();
	//   	}
	// }

	// public function testNewUserCommentVote(){
			
	// 	$user = [
	// 	'email' 			=>	'example@website.ca',
 //        'password'			=>	'abcd1234',
 //        'first_name'		=>	'Joe',
 //        'middle_name'		=>	'Red',
 //        'last_name'			=>	'Truck',
 //        'ethnic_origin_id'	=>	null,
 //        'date_of_birth'		=>	null,
 //        'public'			=>	0,

 //       	];

	// 	$this->call('POST', '/api/user', $user);
	// 	$this->post('/authenticate', ['email' => 'example@website.ca', 'password' => 'abcd1234']);
		
	// 	$this->get('/settings');
	// 	$arrayOfSettings = json_decode($this->response->getContent());
	// 	$id = $arrayOfSettings->user->id;
	// 	Auth::loginUsingId($id);
	// 	DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => 4));


	// 	$commentvote = ['position' => 1, 'comment_id' => 1];
	// 	$response = $this->call('POST', '/api/comment_vote', $commentvote);
	// 	if($this->response->getStatusCode() != 200){
			
	// 		$thisdatais = "\n\ntestNewUserCommentVoteResponse: ";
	// 			$pos = strpos($response, '<span class="exception_message">');
	// 		$data = substr($response, $pos, ( strpos($response, PHP_EOL, $pos) ) - $pos);
	// 		echo $thisdatais;
	// 		echo $data;
	// 		$posOfStackTrace = strpos($response, '<ol class="traces list_exception">');
	// 		$dataOfStackTrace = substr($response, $posOfStackTrace, ( strpos($response, '</li>', $posOfStackTrace) ) - $posOfStackTrace);	
	// 		echo $dataOfStackTrace;

	// 		$this->assertResponseOk();
	// 	}
	// } 

}
