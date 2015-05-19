<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Motion;
use App\Comment;
use Auth;
use DB;
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
		if(!Auth::user()->can('create-vote')){
			Auth::user()->addUserRoleByName('citizen'); //FOR THE CONFERENCE
		}
			
		if(Auth::check() && Auth::user()->can('create-vote')){ //Logged in user will want to see if they voted on these things
			$motions = Motion::with(['votes'=>function($query){
				$query->where('user_id',Auth::user()->id);
			}])->get();
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
		if($id=='rand'){
			$motion = Motion::orderByRaw('RAND()')->first();
		} else {
			$motion  = Motion::with(['votes' => function($q) {
	                // The post_id foreign key is needed, 
	                // so Eloquent could rearrange the relationship between them
	                $q->select(array(DB::raw("count(*) as count, position"),"motion_id"))
	                  ->groupBy("position");
	            }])
		        ->find($id);
		}
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

	public function getComments($id){

		$comments = DB::table('comments')->join('votes','comments.vote_id','=','votes.id')->join('users','votes.user_id','=','users.id')
						->select('user_id','text','first_name','last_name','position','public','comments.id')->where('comments.motion_id',$id)->whereNull('comments.deleted_at')->get();

		if(Auth::user()->can('view-comment')){ //A full admin who can see whatever
			return $comments;
		} else {
			$redactedComments = array();
			foreach($comments as $comment){
				$redactedComment['text'] = $comment->text;
				$redactedComment['position'] = $comment->position;
				if($comment->public){
					$redactedComment['first_name'] = $comment->text;
					$redactedComment['last_name'] = $comment->position;
					$redactedComment['user_id'] = $comment->user_id;
				}
				$redactedComments[] = $redactedComment;
			}
			return $comments;
		}
	}
}
