<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use App\Comment;
use App\User;
use App\Motion;
use App\Vote;
use DB;

use App\Services\Registrar;
use Hash;
use Zizaco\Entrust\Entrust;
use Illuminate\Http\Response;



class CommentController extends Controller {

	protected $rules = [
		'approved'		=>	'boolean',
        'motion_id' 	=>	'integer|required',
        'text'			=>	'required',
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
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
			Auth::loginUsingId(1);
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
			$comment = Comment::with('vote','user')->where('comments.id',$id)->get();
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}



}
