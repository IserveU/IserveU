<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Comment;

class CommentController extends Controller {

	protected $rules = [
		'approved'		=>	'boolean',
        'motion_id' 	=>	'integer',
        'text'			=>	'alpha_dash',
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
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Auth::user()->can('create-comment')){
			$input = Request::all();
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				$newComment = Motion::create($input); //Does the fields specified as fillable in the model
				$newComment->user_id = Auth::user()->id;
				return $input; //Need to add more
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
			$comment = Comment::with('vote.user')->find($id)->get();
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
