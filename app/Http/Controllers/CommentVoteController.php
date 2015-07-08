<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

use App\CommentVote;
use App\Comment;
use App\Vote;

use Auth;
use DB;
use Validator;

class CommentVoteController extends ApiController {


	/**
	 * Display a listing of the comment votes for this user
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::user()->can('view-comment_votes')){ //Administrator able to see any comment vote
			return CommentVote::all();	
		}		
		return CommentVote::where('user_id',Auth::user()->id)->select('comment_id','position')->get(); //Get standard users comment votes	
	}

	/**	
	 * Show the rules for creating a new vote comment.
	 *
	 * @return Response
	 */
	public function create()
	{	
		if(!Auth::user()->can('create-comment_votes')){
			abort(401,'You do not have permission to vote on a comment');			
		}
		return (new CommentVote)->fields;
	}

	/**
	 * Store a newly created resource in storage. Requires a post with 'comment_id' and 'position'
	 *
	 * @return Response
	 */

	public function store(){
		//Check user permissions
		if(!Auth::user()->can('create-comment_votes')){
			abort(401,'You do not have permission to vote on a comment');
		}

		//Check validation
		$input = Request::all();
		if(!isset($input['comment_id'])){
			abort(422,'comment_id is required');
		}

		//Gets the comment that is to be voted on
		$comment = Comment::find($input['comment_id']); //Does the fields specified as fillable in the model
		if(!$comment){
			abort(403,'There is no comment with the id of '.$input['comment_id']);
		}

		//Check logged in user has voted, and on this comment's motion
		$vote = Vote::where('user_id',Auth::user()->id)->where('motion_id',$comment->motion_id)->first();
		if(!$vote){
			abort(403,'User must vote before posting comment');
		}

		$commentVote  = new CommentVote($input);
		$commentVote->comment_id = 	$comment->id;
		$commentVote->vote_id = 	$vote->id;

		if(!$commentVote->save()){
			abort(403,$commentVote->errors);
		}

		return $commentVote;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(CommentVote $commentVote)
	{
		return $commentVote;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(CommentVote $commentVote)
	{
		if(!Auth::user()->can('create-comment_votes')){
			abort(401,'You do not have permission to edit votes on comments');			
		}

		if(Auth::user()->id != $commentVote->vote->user->id){ //Current user
			abort(401,'You do not have permission to edit the comment vote ('.$commentVote->id.')');
		}
		
		return $commentVote->fields;
	}

	/**
	 * Update the specified resource in storage. Requires comment_vote_id as id
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CommentVote $commentVote)
	{
		//Check user permissions
		if(!Auth::user()->can('create-comment_votes')){
			abort(401,'You do not have permission to vote on a comment');
		}

		// check that the user_id is correct
		if($commentVote->user_id != Auth::user()->id){ //->where('user_id',Auth::user()->id)
			abort(401,"The user with the id of ".Auth::user()->id." did not create the comment_vote with the id of (".$commentVote->id.")");
		}

		//Check validation
		$input = Request::only('position'); 

		//Time to edit vote
		$commentVote->position = $input['position'];

		if(!$commentVote->save()){
			abort(403,$commentVote->errors);
		}
		
		return $commentVote; 	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(CommentVote $commentVote)
	{

		if(Auth::user()->id!=$commentVote->user_id && !Auth::user()->can('delete-comment_votes')){
			abort(401,"User does not have permission to delete this Comment Vote");
		}	

		$commentVote->forceDelete(); //There are no things relying on this

		return $commentVote;	
	}

}
