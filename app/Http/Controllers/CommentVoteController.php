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
		if(Auth::user()->can('view-commentvotes')){ //Administrator able to see any comment vote
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
		if(!Auth::user()->can('create-commentvotes')){
			abort(401,'You do not have permission to vote on a comment');			
		}

		return (new CommentVote)->setRules();
	}

	/**
	 * Store a newly created resource in storage. Requires a post with 'comment_id' and 'position'
	 *
	 * @return Response
	 */

	public function store(){
		//Check user permissions
		if(!Auth::user()->can('create-commentvotes')){
			abort(401,'You do not have permission to vote on a comment');
		}

		//Check validation
		$input = Request::all();
		$commentVote  = new CommentVote($input);

		$commentVote->setRules(['comment_id','position']); //Before going any further these are required
		if(!$commentVote->validate()){
			abort(403,$commentVote->errors);
		}

		//Gets the comment that is to be voted on
		$comment = Comment::find($input['comment_id']); //Does the fields specified as fillable in the model
		if(!$comment){
			abort(403,'There is no comment with the id of '.$input['comment_id']);
		}
		$commentVote->comment_id = $comment->id;

		//Check logged in user has voted, and on this comment's motion
		$vote = Vote::where('user_id',Auth::user()->id)->where('motion_id',$comment->motion_id)->first();
		if(!$vote){
			abort(403,'User must vote before posting comment');
		}
		
		$commentVote->vote_id = $vote->id;
		if(!$commentVote->save()){
			abort(403,$commentVote->errors); //Checks if this combo of keys has already been done
		}

		return $commentVote;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$commentVote = CommentVote::find($id);
		if(Auth::user()->can('view-commentvotes')){ //Administrator able to see any comment vote
			return $commentVote;
		}
		if(Auth::user()->id == $commentVote->user_id){ //Current user
			return $commentVote;
		}
		abort(401,'You do not have permission to vote on a comment');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(!Auth::user()->can('create-commentvotes')){
			abort(401,'You do not have permission to edit votes on comments');			
		}

		$commentVote = CommentVote::find($id);
		if(!$commentVote){
			abort(403,"There is no comment vote with the ID of ($id)");
		}

		if(Auth::user()->id != $commentVote->vote->user->id){ //Current user
			abort(401,'You do not have permission to edit this comment vote');
		}
		
		return $commentVote->setRules();
	}

	/**
	 * Update the specified resource in storage. Requires comment_vote_id as id
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//Check user permissions
		if(!Auth::user()->can('create-commentvotes')){
			abort(401,'You do not have permission to vote on a comment');
		}

		//Find the existing comment vote, check that the user_id is correct
		$commentVote = CommentVote::where('id',$id)->first();
		
		if(!$commentVote){
			abort(401,"This Comment Vote does not exits ($id) you are using the wrong API call or passing an invalid comment_vote_id");
		}

		if($commentVote->user_id != Auth::user()->id){ //->where('user_id',Auth::user()->id)
			abort(401,"The user with the id of ".Auth::user()->id." did not create the comment_vote with the id of ($id)");
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
	public function destroy($id)
	{
		$commentVote = CommentVote::findOrFail($id);
		if(Auth::user()->id!=$commentVote->user_id || !Auth::user()->can('delete-comments-vote')){
			abort(401,"User does not have permission to delete this Comment Vote");
		}	

		$commentVote->forceDelete(); //There are no things relying on this
	}

}
