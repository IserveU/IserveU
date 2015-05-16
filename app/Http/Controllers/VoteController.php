<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Vote;

class VoteController extends Controller {

	protected $rules = [
		'motion_id' 	=>	'integer',
		'position'		=>	'integer'
	];

	public function rules(){
		return $this->rules;
    }

   	public function __construct(){
		$this->middleware('auth'); //Nothing can be done without being logged in
	} 


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::user()->can('show-vote')){ //Tracking a vote
			$vote = Vote::find($id);
			return($vote);
		} else{
			$vote = Vote::where('id',$id)->where('user_id',Auth::user()->id)->firstOrFail(); //This person has no right to see this vote
			return($vote);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){

		if(!Auth::user()->can('create-vote')){
			return array('message'=>'You do not have permission to place a vote');
		} 
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		if(Auth::user()->can('create-vote')){
			$input = Request::all();
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				$vote = Vote::firstOrNew(['motion_id'=>$input['motion_id'],'user_id'=>Auth::user()->id]);
				$vote->position = $input['position'];
				$vote->save();
				return $vote;
			}
		} else {
			return array('message'=>'You do not have permission to place a vote');
		}
	}

	/**
	 * Display the specified resource. User with decent permissions can see who posses other people votes, or you can see your own vote.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{	
		if(Auth::user()->can('show-vote')){ //Is a person who can review votes
			$vote = Vote::find($id);
			return($vote);
		} else {	//This is the person who cast the vote
			$vote = Vote::where('id',$id)->where('user_id',Auth::user()->id)->get(); //This person has no right to see this vote
			return($vote);
		}	
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$vote = Vote::where('id',$id)->where('user_id',Auth::user()->id)->get(); //This person has no right to edit their own vote
		
		return($vote);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(Auth::user()->can('create-vote')){
			$input = Request::all();
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				$vote = Vote::firstOrNew(['motion_id'=>$input['motion_id'],'user_id'=>Auth::user()->id]);
				$vote->position = $input['position'];
				$vote->save();
				return $vote;
			}
		} else {
			return array('message'=>'You do not have permission to update a vote');
		}
	}

	/**
	 * You can't delete a vote, just switch to abstain
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->can('create-vote')){
			$input = Request::all();
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				$vote = Vote::firstOrNew(['motion_id'=>$input['motion_id'],'user_id'=>Auth::user()->id]);
				$vote->position = 0;
				$vote->save();
				return $vote;
			}
		} else {
			return array('message'=>'You do not have permission to abstain a vote');
		}
	}

}
