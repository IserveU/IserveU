<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Vote;
use Request;
use Auth;
use Hash;
use Zizaco\Entrust\Entrust;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


use App\Transformers\UserTransformer;  //Not doing anything at the moment


class UserController extends ApiController {

	public function __construct()
	{
		$this->middleware('jwt.auth',['except'=>['create','store', 'authenticatedUser','resetPassword']]);
	} 

	/**
	 * Display a listing of users who have set their profiles to be public. Public profiles can be defered to and their names show up
	 * when they comment/vote on things
	 *
	 * @return Response
	 */
	public function index() {
		$limit = Request::get('limit') ?: 50;

		if (Auth::user()->can('show-users')) { //An admin able to see all users
			$users = User::paginate($limit);
			return $users;
		}

		//Other people can see a list of the public users
		$users = User::arePublic()->paginate($limit);
		return $users;
			
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create(){
		return (new User)->fields;
	}

	/**
	 * Store a newly created resource in storage, this will be created by the user (not an admin)
	 *
	 * @return Response
	 */
	public function store(){
		//Get input less the forgery token
		$input = Request::except('_token');

		//Create a new user and fill secure fields
		$newUser = (new User)->secureFill($input);


		if(!$newUser->save()){
			abort(400,$newUser->errors);
		}

		Auth::loginUsingId($newUser->id);

		$propertyId = Request::get('property_id');
		if($propertyId){ //A property ID field has been submitted
			$newUser->properties()->attach($propertyId); //If the property ID has been chosen, add it to the property_user table
		}

		$newUser->addUserRoleByName('unverified');
		$token = JWTAuth::fromUser($newUser);

		$user = $newUser->toArray();
		return response()->json(compact('token','user'));
	}

	/**
	 * Display the user, but only if they are public or if the user logged in is this user (they are viewing/editing their own profile to see what it would look like if public)
	 *
	 * @param  User  $user
	 * @return Response
	 */
	public function show(User $user){
		if(!$user->public && $user->id != Auth::user()->id && !Auth::user()->can('show-users')){
			abort(401,'You do not have permission to view this non-public user');
		}
		return $user;
	}

	/**
	 * Can't update most of the fields once they have been verified, only email/password/public.
	 * If they go to update their name(s), DOB or any other details that shouldn't change they need to resend us government issued Photo ID
	 * If they update their address we will need to reset their verified to until today, and check the governments database
	 *
	 * @param  User  $user
	 * @return Response
	 */
	public function edit(User $user){
		//Check it is this user, if not that this is an admin that can edit users
		if($user->id != Auth::user()->id && !Auth::user()->can('administrate-users')){
			abort(401,'You do not have permission to edit this user');
		}

		// $item = new Item($user,$userTransformer);

		// $data = $fractal->createData($item)->toArray();

		return $user->fields;
	}


	/**
	 * Can't update most of the fields once they have been verified, only email/password/pubilc.
	 *
	 * @param  User   		$user
	 * @return Response
	 */
	public function update(User $user){

		if($user->id != Auth::user()->id && !Auth::user()->can('administrate-users')){
			abort(401,'You do not have permission to edit this user');
		}

		//If the user has the authentication level, they can change some things
		$user->secureFill(Request::except('token'));

		if(!$user->save()){ //Validation failed
			abort(400,$user->errors);
		}

		$propertyId = Request::get('property_id');
		if($propertyId){ //A property ID field has been submitted
			$propertyExists = $user->properties()->where('id',$propertyId)->count(); //This property record 
			if(!$propertyExists){
				$user->properties()->attach($propertyId); //If the property ID has been chosen, and it's not already in the table, add it to the property_user table
			}
		}

		$user->save();
		return $user;		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(User $user){

		if(Auth::user()->id != $user->id && !Auth::user()->can('delete-users')){
			abort(401,'You do not have permission to delete this user');
		}


	 	$user->delete(); //We want to leave the voting/comment record in tact as an anonomous vote
	 	return $user;
	}

}