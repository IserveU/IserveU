<?php namespace App\Http\Controllers;

// use App\Http\Requests;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;


use Auth;
use DB;
use Setting;

use Illuminate\Http\Request;

use App\Http\Requests\CreateMotionRequest;
use App\Http\Requests\DestroyMotionRequest;
use App\Http\Requests\EditMotionRequest;
use App\Http\Requests\ShowMotionRequest;
use App\Http\Requests\StoreMotionRequest;
use App\Http\Requests\UpdateMotionRequest;

use App\Transformers\MotionTransformer;

use App\MotionRank;
use App\Motion;
use App\Comment;
use App\CommentVote;
use App\Vote;


class MotionController extends ApiController {

	protected $motionTransformer;

	function __construct(MotionTransformer $motionTransformer)
	{
		$this->motionTransformer = $motionTransformer;
		$this->middleware('jwt.auth',['except'=>['index','show']]);
	}

	/**
	 * Display a listing of the resource. If the user is logged in they will see the position they took on votes
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{	
		$filters = $request->all();
		$limit = $request->get('limit') ?: 10;

		if( Auth::check() ){ //Logged in user will want to see if they voted on these things
			$motions = Motion::with(['votes' => function($query){
				$query->where('user_id',Auth::user()->id);
			}]);
		} else {
			$motions = Motion::where('id','>',0);
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

		$paginator = $motions->simplePaginate($limit);
		$motions   = $this->motionTransformer->transformCollection( $paginator->all() );

		return array_merge(['data' => $motions], ['next_page_url' => $paginator->nextPageUrl() ]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(CreateMotionRequest $request){
		return (new Motion)->fields;	// don't really need these routes 	
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreMotionRequest $request)
	{
		$motion = (new Motion)->secureFill( $request->all() ); //Does the fields specified as fillable in the model

		if(!$motion->user_id){ /* Secure fill populates this if the user is an admin*/
			$motion->user_id = Auth::user()->id;
		}

		if(!$motion->save()){
		 	abort(403,$motion->errors);
		}

     	return $this->motionTransformer->transform($motion);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(ShowMotionRequest $request, Motion $motion)
	{
		return $this->motionTransformer->transform($motion);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(EditMotionRequest $request, Motion $motion)
	{
		return $motion->fields;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(UpdateMotionRequest $request, Motion $motion)
	{
		$motion->secureFill( $request->all() );

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
	public function destroy(DestroyMotionRequest $request, Motion $motion)
	{
		$votes = $motion->votes;
		
		if(!$votes){ //Has not recieved votes
			$motion->forceDelete();
			return $motion;
		} 

		$motion->status = 0;
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
