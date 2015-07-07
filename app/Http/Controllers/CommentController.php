<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Auth;
use App\Comment;
use App\User;
use App\Vote;
use App\Motion;
use DB;
use Validator;


class CommentController extends ApiController {

	/**
	 * Display a listing of comments, be sure to hide the user_id or identifying features if this person is not logged in
	 *
	 * @return Response
	 */
	public function index(){
		$comments = Comment::with('vote')->get(); //'vote.user','commentVotes',
		return $comments;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		return (new Comment)->setRules();
	}

	/**
	 * Store a newly created resource in storage. Requires the vote_id to be submitted
	 *
	 * @param int vote_id the comment will be attached to
	 * @return Response
	 */
	public function store(){

		if(!Auth::user()->can('create-comments')){
			abort(401,'You do not have permission to write a comment');
		}

		$vote = Vote::find(Request::get('vote_id'));
		if(!$vote){
			abort(403,"There is no vote with the provided ID of (".Request::get('vote_id').")");
		}

		if($vote->user_id != Auth::user()->id){
			abort(403,"You can not comment tied to another users vote");
		}
		
		$input = Request::all();
		$comment = new Comment($input);
		$comment->vote_id = $vote->id;
		if(!$comment->save()){
			abort(403,$comment->errors);
		}
		return $comment;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		$comment = Comment::find($id);
		if(!$comment){
			abort(403,'Comment does not exist');
		}

		return $comment;
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id to edit this
	 * @return Response
	 */
	public function edit($id)
	{
		if(!Auth::user()->can('create-comments')){
			abort(403,'You do not have permission to update a comment');
		}

		$comment = Comment::find($id);
		if(!$comment){
			abort(400,'Comment does not exist');
		}

		return $comment->setRules();
	}
	/**
	 * Update the specified resource in storage. If it has been deleted, this will undelete it.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(!Auth::user()->can('create-comments')){
			abort(401,'You do not have permission to update a comment');
		}

		$comment = Comment::withTrashed()->find($id);
		if(!$comment){
			abort(400,'Comment does not exist');
		}

		if($comment->user->id != Auth::user()->id && !Auth::user()->can('edit-comment')){
			abort(401,'User does not have permission to edit this comment');
		}

		if($comment->trashed()){ //Undelete comment if you update a comment you deleted
			$comment->deleted_at = null; //restore() isn't working either
			$comment->save();
		}

		$input = Request::all();
		$comment->text = $input['text'];
		

		$tempcheck = $comment->rules;

		if(!$comment->save()){ //Validation failed show errors
			abort(403,$comment->errors);
		}

		return $comment;
	}



	/**
	 * Remove the specified resource from storage, if run twice it permanently deletes it
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$comment = Comment::withTrashed()->with('vote.user')->find($id);
		if(!$comment){
			abort(403,"Comment does not exist, permanently deleted");
		}
		if($comment->user->id != Auth::user()->id && !Auth::user()->can('delete-comments')){
			abort(401,'User does not have permission to delete this comment');
		}

		if($comment->trashed()){ //If it is soft deleted, this will permanently delete it
			$comment->forceDelete();
		}
		
		$comment->delete();

		return $comment;
	}
}