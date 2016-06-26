<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Requests\Vote\DestroyVoteRequest;
use App\Http\Requests\Vote\ShowVoteRequest;
use App\Http\Requests\Vote\StoreVoteRequest;
use App\Http\Requests\Vote\UpdateVoteRequest;
use App\Http\Requests\Vote\IndexVoteRequest;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Vote;
use App\Events\UserChangedVote;

class VoteController extends ApiController {


	function __construct()
	{
		$this->middleware('jwt.auth',['except'=>['index','show']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(IndexVoteRequest $request)
	{

		return Vote::all();	
		
		if(Auth::user()->can('view-vote')){ //Administrator able to see any vote
			return Vote::all();	
		}		
		return Vote::where('user_id',Auth::user()->id)->get(); //Get standard users comment votes	
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreVoteRequest $request)
	{
		$vote  = new Vote($request->all());
		$vote->user_id = Auth::user()->id;	

 		if(!$vote->save()){
			abort(403,$vote->errors);
		}
			
		return $vote;
	}

	/**
	 * Display the specified resource. User with decent permissions can see who posses other people votes, or you can see your own vote.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Vote $vote, ShowVoteRequest $request)
	{
		if(Auth::user()->can('show-vote')){ //Is a person who can review votes
			return $vote;
		}

		if($vote->user_id != Auth::user()->id){		//This is not the person who cast the vote
			abort(401,"You do not have permission to see this vote");
		}
		
		return $vote; //This person has no right to see this vote
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Vote $vote, UpdateVoteRequest $request)
	{
		//Check if the user has permission to cast votes
		if(!Auth::user()->can('create-vote')){
			abort(401,'You do not have permission to update a vote');
		}

		if($vote->user_id != Auth::user()->id){
			abort(401,"This user does not have permission to edit this vote");
		}

		//Validate the input
		$vote->secureFill(Request::all());

 		if(!$vote->save()){
			abort(403,$vote->errors);
		}
		//event(new UserChangedVote($vote));
		return $vote;
	}

	/**
	 * You can't delete a vote, just switch to abstain
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Vote $vote, DestroyVoteRequest $request)
	{

		$vote->position = 0;
		$vote->save();
		return $vote;
		
	}

}
