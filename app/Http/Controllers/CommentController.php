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


class CommentController extends Controller {
	protected $rules = [
		'approved'		=>	'boolean',
        'motion_id' 	=>	'integer',
        //'text'			=>	'alpha_dash', //Do we need validation on the text input? It doesn't like spaces and returns an error
        'vote_id'		=>	'integer'
	];
	public function rules(){
		return $this->rules;
    }
	/**
	 * Display a listing of comments, be sure to hide the user_id or identifying features if this person is not logged in
	 *
	 * @return Response
	 */
	public function index(){
		if(Auth::user()->can('view-comment')){ // Full deal
			$comments = Comment::with('vote.user')->get();
		} else {
			$comments = Comment::all(); // Sees anomous comments, need to get the data of the public profiles as OK
		}
		return $comments;
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
		if(!Auth::user()->can('create-comment')){ //For the conference
			Auth::user()->addUserRoleByName('citizen');
		}

		if(Auth::user()->can('create-comment')){
			$input = Request::all();
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {

				$vote = Vote::where('user_id',Auth::user()->id)
								->where('motion_id',$input['motion_id'])
								->first();		

				if($vote){
					$input['vote_id'] = $vote->id;
					$newComment = Comment::create($input); //Does the fields specified as fillable in the model
					$newComment->vote_id = $vote->id;
					$newComment->save();
				} else {
					abort(401,'User must vote before posting comment');
				}

				return $newComment; 
			}
		} else {
			return array('message'=>'You do not have permission to write a comment');
		}
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::user()->can('view-comment')){ // Full deal
			$comment = Comment::with('vote.user')->find($id);
		} else {
			$comment = Comment::find($id); // Sees anomous comments, need to get the data of the public profiles as OK
		}
		
		return $comment;
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$comment = Comment::find($id);
		return $comment;
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(Auth::user()->can('create-comment')){
			$input = Request::all();
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {

				$comment = Comment::find($id); 

				if($comment->vote->user->id == Auth::user()->id){
					$comment->text = $input['text'];
					$comment->save();
				} else {
					abort(401,'User must vote before posting comment');
				}

				return $comment; 
			}
		} else {
			abort(401,'You do not have permission to update a comment');
		}
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->can('delete-comment')){ //Administrator able to delete any comment they want
			$comment = Comment::find($id);
			$comment->delete();
		} else if(Auth::user()->can('create-comment')){
			$comment = Comment::with('vote.user')->find($id);
			if($comment->vote->user->id == Auth::user()->id){
				$comment->delete();
				return array('message'=>'You deleted your comment');
			} else {
				abort(401,"You do not have permission to delete other users comments, even if you really want to");
			}
		} else {
			return array('message'=>'You do not have permission to delete comments');
		}
	}
}