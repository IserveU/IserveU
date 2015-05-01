<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Motion;
use Illuminate\Http\Request;

class MotionController extends Controller {

	protected $rules = [
		'title' 			=>	'alpha_dash|required|min:8|unique',
        'active'			=>	'boolean',
        'closing' 			=>	'date',
        'text'				=>	'alpha_dash',
        'user_id'			=>	'integer'
	];

	public function rules(){
		return $this->rules;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$motions = Motion::all();
		return $motions;
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
	public function store()
	{
		if(Auth::user()->can('create-motion')){
			$input = Request::all();
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				$newMotion = Motion::create($input); //Does the fields specified as fillable in the model
				$nemMotion->user_id = Auth::user()->id;
				if(Request::get('active') && Auth::user()->can('edit-motion')){ //To set things active this has to be a motion administrator
					$newMotion->active = 1;
					$oneWeek = new DateTime();
					$oneWeek->add(new DateInterval('P7D'));
					$newMotion->closing = $oneWeek->format("Y-m-d 19:i:00"); //want to make sure that we don't have a system that forces people to be awake at 4:30 am
				}
				return $input;
			}
		} else {
			return array('message'=>'You do not have permission to create a motion');
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
		$motion = Motion::with('user', 'comments', 'votes')->find($id)->get(); // HEY RYAN, we want people commenting to be able to do so anonomoously unless they have the pubilc boolean set. Would you rework this data OR use AJAX to load each of the motion, the votes and the comments.
		return $motion;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$motion = Motion::findOrFail($id);
		if(Auth::user()->can('edit-motion')){//Site administrator, can set a motion to active
			$motion->setHidden(array('id','user_id'));
			return $motion;
		} else if(Auth::user()->id==$motion->user_id && Auth::user()->can('create-motion')){ // Standard user can't set active, should check just in case they lost permission to create motions
 			$motion->setHidden(array('id','user_id','active'));
 			return $motion;
		} else {
			return array('message'=>'Permission Denied'); 
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$motion = Motion::findOrFail($id);
		if(Auth::user()->id == $motion->user_id || Auth::user()->can('edit-motion')){
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				$motion->title = Request::get('title');
				$motion->text = Request::get('text');

				if(Request::get('active') && Auth::user()->can('edit-motion')){ //To set things active this has to be a motion administrator
					$motion->active = 1;
					$oneWeek = new DateTime();
					$oneWeek->add(new DateInterval('P7D'));
					$motion->closing = $oneWeek->format("Y-m-d 19:i:00"); //want to make sure that we don't have a system that forces people to be awake at 4:30 am
				}
		
				$motion->save();
				return $motion;
			}
		} else {
			return array("message"=>"Permission denied to update this record");
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
		$motion = Motion::findOrFail($id);
		if(Auth::user()->id==$motion->user_id || Auth::user()->can('delete-motion')){
			$votes 		= 	Vote::where('motion_id',$id)->count();
			if($votes){ //Has recieved votes
				$motion->active = 0;
				$motion->save();
				$motion->delete(); //Motion kept in the database
			} else { //Has done nothing at all, might as well remove from the database (not soft delete)
				$motion->forceDelete();
			}
		}	
	}

}
