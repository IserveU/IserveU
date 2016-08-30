<?php namespace App\Http\Controllers;


use Auth;
use DB;
use Setting;

use Illuminate\Http\Request;

use App\Http\Requests\Motion\DestroyMotionRequest;
use App\Http\Requests\Motion\ShowMotionRequest;
use App\Http\Requests\Motion\StoreMotionRequest;
use App\Http\Requests\Motion\UpdateMotionRequest;
use App\Http\Requests\Motion\IndexMotionRequest;

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
		$this->middleware('auth:api',['except'=>['index','show']]);
	}

	/**
	 * Display a listing of the resource. If the user is logged in they will see the position they took on votes
	 *
	 * @return Response
	 */
	public function index(IndexMotionRequest $request)
	{	
		$limit = $request->get('limit') ?: 10;


		if( Auth::check() ){ //Logged in user will want to see if they voted on these things

			$motions = Motion::with(['votes' => function($query){
				$query->where('user_id',Auth::user()->id);
			}]);
		
			//dd(DB::getQueryLog());
		} else {
			$motions = (new Motion)->newQuery();
		}

		if($request->has('status')){
			$motions->status($request->input('status'));
		}

		if($request->has('rank_greater_than')){
			$motions->rankGreaterThan($request->input('rank_greater_than'));
		}

		if($request->has('user_id')){
			$motions->writer($request->input('user_id'));
		}

		if($request->has('rank_less_than')){
			$motions->rankLessThan($request->input('rank_less_than'));
		}

		if($request->has('department_id')){
			$motions->department($request->input('department_id'));
		}

		if($request->has('is_current')){
			$motions->current($request->input('is_current'));
		}

		if($request->has('is_expired')){
			$motions->expired($request->input('is_expired'));
		}

		if($request->has('newest')){
			$motions->orderByNewest($request->input('newest'));
		}
	
		if($request->has('oldest')){
			$motions->orderByOldest($request->input('oldest'));
		}

		if($request->has('take')){
			$motions->take($request->input('take'));
		} else {
			$motions->take(1);
		}

		$paginator = $motions->simplePaginate($limit);
		$motions   = $this->motionTransformer->transformCollection( $paginator->all() );

		return array_merge(['data' => $motions], ['next_page_url' => $paginator->nextPageUrl() ]);
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
	public function show(Motion $motion,ShowMotionRequest $request)
	{
		return $this->motionTransformer->transform($motion);
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

		return $this->motionTransformer->transform($motion);
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

		if($motion->user->id != Auth::user()->id && !Auth::user()->can('delete-motion')){
			abort(401,'User does not have permission to restore and delete motions');
		}

		$motion->deleted_at = null; //restore() isn't working either
		if(!$motion->save()){
			abort(400,$motion->errors);
		}

		return $motion;
	}

}
