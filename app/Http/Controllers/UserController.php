<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Request;
use Illuminate\Support\Facades\Validator;
use AuthController;
use App\Services\Registrar;
use Hash; 

class UserController extends Controller {

	protected $rules = [
		'first_name' 		=>	'required|alpha|max:127',
        'middle_name'		=>	'alpha|max:127',
        'last_name' 		=>	'required|alpha|max:127',
        'date_of_birth'		=>	'required|date',
        'email'    			=>	'required|email|max:127|unique:users',
        'password' 			=>	'required|between:8,30|confirmed',
        'ethnic_origin_id'	=>	'integer',
        'public'			=>	'required|boolean',
        'property_id'		=>	'integer|required', /*We might have a problem with this, if people stuggle with our property database */
        'verified_until'	=>	'date', /*Not immediately required, this field MUST be set by a site administrator */ 
        'administrator'		=>	'boolean', /* this MUST be set by a site administrator */ 
        'intrepid'			=>	'boolean'
	];

	//What the a user can see/edit in their own profile (Populate an edit form)
	protected $userVisible = ['first_name', 'middle_name', 'last_name','date_of_birth','email','ethnic_origin_id','public','property_id'];

	public function rules(){
		return $this->rules;
    }

	/**
	 * Display a listing of users who have set their profiles to be public. Public profiles can be defered to and their names show up
	 * when they comment/vote on things
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::where('public',1)->get();
		return $users;
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create(){
		return view('test',array('data'=>$this->rules)); //Just some BS right now that Paige used to test the form
	}

	/**
	 * Store a newly created resource in storage, this will be created by the user
	 *
	 * @return Response
	 */
	public function store(){
		$input = Request::all();
		$validator = Validator::make($input,$this->rules);
		if($validator->fails()){
			return $validator->messages();
		} else {
			$newUser = User::create($input); //Does the fields specified as fillable in the model
			if(false/* $isadmin */){ // To add, administrator authentication
				//It HAS to be 1
				$newUser->administrator 	= Request::get('administrator')=="1"?1:0; 
				//Empty dates should be NULL. If a date has been set, then that means it was once valid. Don't populate this with 0000-00-00 or we'll think that we only have to verify their address and not their identity
				$newUser->verified_until	= Request::get('verified_until')==""?NULL:Request::get('verified_until'); 
				//It HAS to be 1
				$newUser->intrepid			= Request::get('administrator')=="1"?1:0; 
			}
			$newUser->password = Hash::make(Request::get('password'));
			$newUser->save();
			return $input;
		}
	}

	/**
	 * Display the user, but only if they are public or if the user logged in is this user (they are viewing/editing their own profile to see what it would look like if public)
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::find($id);
		if(true /*$user->id == $loggedinid*/){ //Logged in user id = this user ID $user->id == $loggedinid
			$user = User::find($id);
 			return $user; //Showing the user what the general public would see (just with an edit button below it) 
		} else if(false /* $isadmin */){ //You are the site administrator
			$user->setHidden(['password']); // Admin shows every field
			return $user;
		} else if($user->public){ //Returns basic information, name, DOB and if they are an intrepid
			return $user;
		} else { //Not a public profile, or logged in as a person who can override that
			return array('message'=>'this is not a public profile'); //Not sure what format angular messages need
		}
	}

	/**
	 * Can't update most of the fields once they have been verified, only email/password/public.
	 * If they go to update their name(s), DOB or any other details that shouldn't change they need to resend us government issued Photo ID
	 * If they update their address we will need to reset their verified to until today, and check the governments database
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::findOrFail($id);
		if(true /*$user->id == $loggedinid*/){ //Authentication of user ID $user->id == $loggedinid	
			$user->setVisible($this->userVisible); // If the user is logged in they can edit
 			return $user;
		} else if(false /* $isadmin */ ){ //Site administrator
			$user->setHidden('password');
			return $user;
		} else {
			return array('message'=>'Permission Denied'); 
		}
	}

	/**
	 * Can't update most of the fields once they have been verified, only email/password/pubilc.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = User::findOrFail($id);
		if(true /*!$isadmin || $isuser */){  // $user->id == $loggedinid || $isadmin
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				if($user->property_id != Request::get('property_id')){ //They moved
					$yesterday = new \DateTime('Yesterday');
					$user->verified_until = $yesterday->format('Y-m-d');
				}

				if(true /*!$isadmin */){ // To add, administrator authentication
					//It HAS to be 1
					$user->administrator 	= Request::get('administrator')=="1"?1:0; 
					//Empty dates should be NULL. If a date has been set, then that means it was once valid. Don't populate this with 0000-00-00 or we'll think that we only have to verify their address and not their identity
					$user->verified_until	= Request::get('verified_until')==""?NULL:Request::get('verified_until'); 
					//It HAS to be 1
					$user->intrepid			= Request::get('intrepid')=="1"?1:0; 
				}

				$user->password = Hash::make(Request::get('password'));
				$user->date_of_birth = Request::get('date_of_birth');
				$user->first_name = Request::get('first_name');
				$user->middle_name = Request::get('middle_name');
				$user->last_name = Request::get('last_name');

				//If any of the fundamental fields have changed, they need to start verification at the beginning
				$dirty = $user->getDirty();
				foreach ($dirty as $field => $newdata){
					 $olddata = $record->getOriginal($field);
					 if($olddata != $newdata && true/*!$isadmin */){ //TODO let the admin change this without causing this to reset
					 	$user->verified_until = NULL;
					 }
				}

				$user->save();
				return $input;
			}
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
		$user = User::findOrFail($id);
		if(true /* $isuser || $isadmin*/){
			$votes 		= 	Vote::where('user_id',$id)->get();
			$motions 	= 	Motion::where('user_id',$id)->get();
			if($votes->count() && $motions->count()){ //Has made motions/votes
				$user->public = 0;
				$user->save();
				$user->delete(); //We want to leave the voting/comment record in tact as an anonomous vote
			} else { //Has done nothing at all, might as well remove from the database (not soft delete)
				$user->forceDelete();
			}
		}		
	}
}
