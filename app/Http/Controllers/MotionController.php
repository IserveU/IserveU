<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

use App\MotionRank;
use App\Motion;
use App\Comment;
use App\CommentVote;
use App\Vote;
use Auth;
use DB;
use Setting;
use Carbon\Carbon;



class MotionController extends ApiController {

	/**
	 * Display a listing of the resource. If the user is logged in they will see the position they took on votes
	 *
	 * @return Response
	 */
	public function index()
	{	

		$filters = Request::all();
		$limit = Request::get('limit') ?: 30;

		if(Auth::user()->can('create-votes')){ //Logged in user will want to see if they voted on these things
			$motions = Motion::with(['votes' => function($query){
				$query->where('user_id',Auth::user()->id);
			}]);
		} else {
			$motions = Motion::all();
		}

		if(isset($filters['rank_greater_than']) && is_numeric($filters['rank_greater_than'])){
			$motions->rankGreaterThan($filters['rank_greater_than']);
		}

		if(isset($filters['rank_less_than']) && is_numeric($filters['rank_less_than'])){
			$motions->rankLessThan($filters['rank_less_than']);
		}

		if(isset($filters['department_id']) && is_numeric($filters['department_id'])){
			$motions->department($filters['department_id']);
		}

		if(isset($filters['is_active'])){
			$motions->active($filters['is_active']);
		}

		if(isset($filters['is_expired'])){
			$motions->expired($filters['is_expired']);
		}

		if(isset($filters['is_current'])){
			$motions->current($filters['is_current']);
		}

		if(isset($filters['newest'])){
			$motions->orderByNewest($filters['newest']);
		}

		if(isset($filters['oldest'])){
			$motions->orderByOldest($filters['oldest']);
		}

		if(isset($filters['take'])){
			$motions->take($filters['take']);
		} else {
			$motions->take(1);
		}
		return $motions->simplePaginate($limit);
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

		$motion = (new Motion)->secureFill(Request::all()); //Does the fields specified as fillable in the model
	
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
		if(Auth::check()){
			Vote::where('motion_id',$motion->id)->where('user_id',Auth::user()->id)->update(['visited'=>true]);
		}

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

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('administrate-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($id)");
		}

		return $motion->fields;
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

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('administrate-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($id)");
		}

		if(!$motion->active && !$motion->latestRank){ //Motion has closed/expired
			abort(403,'This motion has expired and can not be edited');
		}

		$motion->secureFill(Request::all());

		if(Request::get('active')){ //If you tried to set active, but failed with permissions
			$motion->setActiveAttribute(1);
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

	public function restore($id){
		$motion = Motion::withTrashed()->with('user')->find($id);

		if(!$motion){
			abort(404,'Motion does not exist');
		}

		if($motion->user->id != Auth::user()->id && !Auth::user()->can('administrate-motions')){
			abort(401,'User does not have permission to restore this motion');
		}

		$motion->deleted_at = null; //restore() isn't working either
		if(!$motion->save()){
			abort(400,$motion->errors);
		}

		return $motion;
	}

}
