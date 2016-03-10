<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Vote;
use App\File;
use Auth;
use Hash;
use Zizaco\Entrust\Entrust;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;

use App\Transformers\UserTransformer;  

class UserController extends ApiController {

	protected $userTransformer;

	public function __construct(UserTransformer $userTransformer)
	{	
		$this->userTransformer = $userTransformer;
		$this->middleware('jwt.auth',['except'=>['create','store', 'authenticatedUser','resetPassword']]);
	} 

	/**
	 * Display a listing of users who have set their profiles to be public. Public profiles can be defered to and their names show up
	 * when they comment/vote on things
	 *
	 * @return Response
	 */
	public function index(Request $request) {

		$filters = $request->all();
		$limit = $request->get('limit') ?: 50;

		if (Auth::user()->can('show-users')) { //An admin able to see all users
			$users = User::whereExists(function($query){
				$query->where('id','>',0);
			});
		} else {
			//Other people can see a list of the public users
			$users = User::arePublic();
		}

		if(isset($filters['verified'])){
			$users->verified($filters['verified']);
		}

		if(isset($filters['unverified'])){
			$users->unverified($filters['unverified']);
		}

		if(isset($filters['address_unverified'])){
			$users->addressUnverified($filters['address_unverified']);
		}

		if(isset($filters['address_not_set'])){
			$users->addressNotSet($filters['address_not_set']);
		}

		return $users->paginate($limit);
			
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
	public function store(Request $request){
		//Get input less the forgery token
		$input = $request->except('_token');

		//Create a new user and fill secure fields
		$newUser = (new User)->secureFill($input);


		if(!$newUser->save()){
			abort(400,$newUser->errors);
		}


		Auth::loginUsingId($newUser->id);

		$propertyId = $request->get('property_id');
		if($propertyId){ //A property ID field has been submitted
			$newUser->property_id = $propertyId; //If the property ID has been chosen, add it to the property_user table
		}

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

		return $this->userTransformer->transform( $user->toArray() );
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
	public function update(User $user, Request $request){

		if($user->id != Auth::user()->id && !Auth::user()->can('administrate-users')){
			abort(401,'You do not have permission to edit this user');
		}

		//If the user has the authentication level, they can change some things
		$user->secureFill($request->except('token'));

		if(!$user->save()){ //Validation failed
			abort(400,$user->errors);
		}


		
		if($request->file('government_identification')){
			$file = new File;
	      	$file->uploadFile('government_identification','government_identification',$request);		
			if(!$file->save()){
			 	abort(403,$file->errors);
	      	}
	      	$user->government_identification_id		=	$file->id;
		}

		if($request->file('avatar')){
			$file = new File;
	      	$file->uploadFile('avatars','avatar',$request);		
			if(!$file->save()){
			 	abort(403,$file->errors);
	      	}
	      	$user->government_identification_id		=	$file->id;
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