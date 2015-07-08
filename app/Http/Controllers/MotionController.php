<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Motion;
use App\Comment;
use App\CommentVote;
use App\Vote;
use Auth;
use DB;
use \Cache;


class MotionController extends ApiController {

	/**
	 * Display a listing of the resource. If the user is logged in they will see the position they took on votes
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::user()->can('create-votes')){ //Logged in user will want to see if they voted on these things
			$motions = Motion::with(['votes'=>function($query){
				return $query->where('user_id',Auth::user()->id);
			}])->get();
			return $motions;
		} else {
			$motions = Motion::all();
		}
		return $motions;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		if(!Auth::user()->can('create-motions')){
			abort(401,'You do not have permission to create a motion');
		}

		return (new Motion)->fields;		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(!Auth::user()->can('create-motions')){
			abort(401,'You do not have permission to create a motion');
		}

	
		$motion = (new Motion)->secureFill(Request::all('active')); //Does the fields specified as fillable in the model

		$motion->active = 1;
		
		if(!$motion->user_id){ /* Secure fill populates this if the user is an admin*/
			$motion->user_id = Auth::user()->id;
		}

		if(!$motion->save()){
		 	abort(403,$motion->errors);
		}
     	
     	return $motion;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Motion $motion)
	{	
		$motion  = Motion::with(['votes' => function($q) {
            // The post_id foreign key is needed, 
            // so Eloquent could rearrange the relationship between them
            $q->select(array(DB::raw("count(*) as count, position"),"motion_id"))
              ->groupBy("position");
        }])
        ->find($motion->id); 

		return $motion;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Motion $motion)
	{
		if(!Auth::user()->can('create-motions')){
			abort(403,'You do not have permission to create/update motions');
		}

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('edit-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($id)");
		}

		return $motion->rules;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Motion $motion)
	{
		if(!Auth::user()->can('create-motions')){
			abort(403,'You do not have permission to update a motion');
		}

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('edit-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($id)");
		}

		$motion->title = Request::get('title');
		$motion->text = Request::get('text');

		if(Request::get('active') && !$motion->setActiveAttribute(Request::get('active'))){ //If you tried to set active, but failed with permissions
			abort(401,"This user does not have permission to set motions as active");
		}
		
		if(!$motion->save()){
		 	abort(403,$motion->errors);
		}

		return $motion;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Motion $motion)
	{
		if(Auth::user()->id != $motion->user_id && !Auth::user()->can('delete-motions')){
			abort(401,"You do not have permission to delete this motion");
		}

		$votes = $motion->votes;
		
		if(!$votes){ //Has not recieved votes
			$motion->forceDelete();
			return $motion;
		} 

		$motion->active = 0;
		$motion->save();
		$motion->delete(); //Motion kept in the database	
		return $motion;
	}


	/**
	 * Return the comment for a motion with the user in questions comment in it's own array, as well as the current users votes
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function getComments($id){

		$comments = array();

		if(Auth::user()->can('view-comments')){ //A full admin who can see whatever
			$comments['agreeComments']		= Comment::with('vote.user','commentVotes')->where('motion_id',$id)->agree()->get()->sortByDesc('commentRank')->toArray();
			$comments['disagreeComments'] 	= 	Comment::with('vote.user','commentVotes')->where('motion_id',$id)->disagree()->get()->sortByDesc('commentRank')->toArray();
		
		} else { //Load the standard cached comments for the page
	
			$comments = Cache::remember('motion'.$id.'_comments', config('app.cachetime'), function() use ($id){
				$comments['agreeComments']		= Comment::with('vote.user','commentVotes')->where('motion_id',$id)->agree()->get()->sortByDesc('commentRank')->toArray();
				$comments['disagreeComments'] 	= 	Comment::with('vote.user','commentVotes')->where('motion_id',$id)->disagree()->get()->sortByDesc('commentRank')->toArray();
				return $comments;
			});
		}

		$comments['thisUsersComment'] = Comment::where('motion_id',$id)->with('vote')->where('user_id',Auth::user()->id)->first();
		$comments['thisUsersCommentVotes'] = CommentVote::where('motion_id',$id)->where('user_id',Auth::user()->id)->get();


		return $comments;
		
	}
}
