<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Services\Registrar;
use Hash;
use Zizaco\Entrust\Entrust;
use Illuminate\Http\Response;


class UserController extends Controller {


	//What the a user can see/edit in their own profile (Populate an edit form)
	//protected $userVisible = ['first_name', 'middle_name', 'last_name','date_of_birth','email','ethnic_origin_id','public'];
	
	protected $rules = [
		'email' 			=>	'email|unique',
        'password'			=>	'min:8',
        'first_name'		=>	'alpha',
        'middle_name'		=>	'alpha',
        'last_name'			=>	'alpha',
        'ethnic_origin_id'	=>	'integer',
        'date_of_birth'		=>	'date',
        'public'			=>	'boolean'        
	];

	public function __construct()
	{
		$this->middleware('auth',['except'=>['create', 'checkLogin','rules']]); //Should be logged in 
	} 

	public function rules(){
		return $this->rules;
    }

	/**
	 * Display a listing of users who have set their profiles to be public. Public profiles can be defered to and their names show up
	 * when they comment/vote on things
	 *
	 * @return Response
	 */
	public function index(){
		if (Auth::check() && Auth::user()->can('show-user')){ //An admin able to see all users
			return User::all();
		} else { //Other people can see a list of the public users
			return User::arePublic()->get();
		}	
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create(){
	
	}

	/**
	 * Store a newly created resource in storage, this will be created by the user (not an admin)
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
			$newUser->password = Hash::make(Request::get('password'));
			$newUser->save();
			$propertyId = Request::get('property_id');
			if($propertyId){ //A property ID field has been submitted
				$user->properties()->attach($propertyId); //If the property ID has been chosen, add it to the property_user table
			}
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
		if(Auth::user()->can('show-user')) { //User admin looking at an account
			$user->setHidden(['password']); // Admin account sees every field apart from password
			return $user;
		} else if(Auth::user()->id == $user->id) { //Current user looking at their own account
			return $user; //Showing the user what the general public would see (just with an edit button below it) 
		} else if($user->public) { //Returns basic information, name, DOB and if they are an intrepid
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
	public function edit($id){
		$user = User::findOrFail($id);
		if(Auth::user()->can('edit-user')){ //Site administrator, do whatever they want
			$user->setHidden('password');
			return $user; //User-edit admin can get all fields
		} else if(Auth::user()->id==$user->id){ // This user, can edit some of their own details
			$user->setVisible($this->userVisible); // If the user is logged in they can edit
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
	public function update($id){
		$user = User::findOrFail($id);
		if(Auth::user()->id == $user->id || Auth::user()->can('edit-user')){
			$validator = Validator::make($input,$this->rules);
			if($validator->fails()){
				return $validator->messages();
			} else {
				$user->password = Hash::make(Request::get('password'));
				$user->date_of_birth = Request::get('date_of_birth');
				$user->first_name = Request::get('first_name');
				$user->middle_name = Request::get('middle_name');
				$user->last_name = Request::get('last_name');

				$propertyId = Request::get('property_id');
				if($propertyId){ //A property ID field has been submitted
					$propertyExists = $user->properties()->where('id',$propertyId)->count(); //This property record 
					if(!$propertyExists){
						$user->properties()->attach($propertyId); //If the property ID has been chosen, and it's not already in the table, add it to the property_user table
					}
				}

				//If any of the fundamental fields have changed, they need to start verification at the beginning
				$dirty = $user->getDirty();
				foreach ($dirty as $field => $newdata){
					 $olddata = $record->getOriginal($field);
					 if($olddata != $newdata && !Auth::user()->can('edit-user')){ // let the admin change this without causing it to reset
						$user->detachRoles($use->roles); //Remove all roles
					 }
				}

				$user->save();
				return $input;
			}
		} else {
			return array("message"=>"Permission denied to store a record");
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		$user = User::findOrFail($id);
		if(Auth::user()->id==$user->id || Auth::user()->can('delete-user')){
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

	public function checkLogin() {
		if(Auth::check()) {
			return Auth::user();
		}
		else {
			return response('not logged in');
		}
	}
}
