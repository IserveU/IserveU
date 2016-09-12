<?php 
namespace App\Http\Controllers\Comment;
use App\Http\Controllers\ApiController;

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

use App\Http\Requests\Comment\DestroyCommentRequest;
use App\Http\Requests\Comment\ShowCommentRequest;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Comment\IndexCommentRequest;

class CommentController extends ApiController {

	/**
	 * Display a listing of comments, be sure to hide the user_id or identifying features if this person is not logged in
	 *
	 * @return Response
	 */
	public function index(IndexCommentRequest $request){

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


		return $comments->sortBy('commentRank')->chunk($input['number'])->reverse();

	}

	/**
	 * Store a newly created resource in storage. Requires the vote_id to be submitted
	 *
	 * @param int vote_id the comment will be attached to
	 * @return Response
	 */
	public function store(StoreCommentRequest $request){
	    
	    //Move into a validation method
        $vote = \App\Vote::with('comment')->findOrFail($request->input('vote_id'));
        if($vote->comment){
            return  response(["error"=>"Already Commented","message"=>"You have already voted and should instead edit your vote"],400); 
        }

		$comment = new Comment($request->all());
		$comment->vote_id = $request->input('vote_id');
		$comment->save();

		return $comment;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(ShowCommentRequest $request, Comment $comment){
		return $comment;
	}


	/**
	 * Update the specified resource in storage. If it has been deleted, this will undelete it.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(UpdateCommentRequest $request, Comment $comment)
	{
		
		$comment->fill($request->except('token'));

		$comment->save();

		return $comment;
	}



	/**
	 * Remove the specified resource from storage, if run twice it permanently deletes it
	 *
	 * @param  Comment  $comment The comment you want to destroy
	 * @return Response
	 */
	public function destroy(DestroyCommentRequest $request, Comment  $comment)
	{
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