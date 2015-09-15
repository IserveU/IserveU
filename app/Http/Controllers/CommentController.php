<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Auth;
use App\Comment;
use App\User;
use App\Vote;
use App\Motion;
use App\CommentVote;
use DB;
use Validator;
use Carbon\Carbon;


class CommentController extends ApiController {

	/**
	 * Display a listing of comments, be sure to hide the user_id or identifying features if this person is not logged in
	 *
	 * @return Response
	 */
	public function index(){

		$input = Request::all();

		if(!isset($input['start_date'])){
			$input['start_date'] = Carbon::today();
		}
		
		if(!isset($input['end_date'])){
			$input['end_date'] = Carbon::tomorrow();
		}

		if(!isset($input['number'])){
			$input['number'] = 1;
		}

		$validator =  Validator::make($input,[
			'start_date'	=> 	'date',
			'end_date'		=>	'date',
			'number'		=>	'integer'
		]);


		if($validator->fails()){
			return $validator->errors();
		}

		$comments = Comment::with('commentvotes','vote')->betweenDates($input['start_date'],$input['end_date'])->get()->sortBy(function($comment){
			return $comment->commentvotes->count();
		});


		return $comments->chunk($input['number']);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		return (new Comment)->fields;
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

		$comment = Comment::onlyTrashed()->where('vote_id',$vote->id)->where('user_id',Auth::user()->id)->first();
		if($comment){
			$comment->restore();
			$comment->text = Request::get('text');
		} else {
			$comment = new Comment(Request::all());
			$comment->vote_id = $vote->id;
		}

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
	public function show(Comment $comment){
		return $comment;
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id to edit this
	 * @return Response
	 */
	public function edit(Comment $comment)
	{
		if(!$comment){
			abort(400,'Comment does not exist');
		}

		if(!Auth::user()->can('create-comments')){
			abort(403,'You do not have permission to update a comment');
		}

		return $comment->fields;
	}
	/**
	 * Update the specified resource in storage. If it has been deleted, this will undelete it.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Comment $comment)
	{
		if(!Auth::user()->can('create-comments')){
			abort(401,'You do not have permission to update a comment');
		}

		if(!$comment){
			abort(400,'Comment does not exist');
		}

		if($comment->user->id != Auth::user()->id && !Auth::user()->can('administrate-comment')){
			abort(401,'User does not have permission to edit this comment');
		}

		$comment->secureFill(Request::except('token'));

		if(!$comment->save()){ //Validation failed show errors
			abort(403,$comment->errors);
		}

		return $comment;
	}



	/**
	 * Remove the specified resource from storage, if run twice it permanently deletes it
	 *
	 * @param  Comment  $comment The comment you want to destroy
	 * @return Response
	 */
	public function destroy(Comment $comment)
	{
		if(!$comment){
			abort(403,"Comment does not exist, permanently deleted");
		}
		if($comment->user->id != Auth::user()->id && !Auth::user()->can('delete-comments')){
			abort(401,'User does not have permission to delete this comment');
		}

		$comment->delete();

		return $comment;
	}

	public function restore($id){
		$comment = Comment::withTrashed()->with('vote.user')->find($id);

		if(!$comment){
			abort(404,'Comment does not exist');
		}

		if($comment->user->id != Auth::user()->id && !Auth::user()->can('administrate-comment')){
			abort(401,'User does not have permission to restore this comment');
		}

		$comment->deleted_at = null; //restore() isn't working either
		if(!$comment->save()){
			abort(400,$comment->errors);
		}

		return $comment;
	}

}