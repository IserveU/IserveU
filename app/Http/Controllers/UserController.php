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

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\ShowUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

use App\Transformers\UserTransformer;

use Setting;

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

		if (Auth::user()->can('show-user')) { //An admin able to see all users
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
	public function create(CreateUserRequest $request){

	}

	/**
	 * Store a newly created resource in storage, this will be created by the user (not an admin)
	 *
	 * @return Response
	 */
	public function store(StoreUserRequest $request){
		//Create a new user and fill secure fields
		$newUser = User::create($request->except('token'));

		if(!Setting::get('security.verify_citizens')){
			$newUser->addUserRoleByName('citizen');
		}

		$token = JWTAuth::fromUser($newUser);

		$user = $newUser->toArray();
		return response()->json(compact('token','user'),200,[],JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 
	 * @param  User  $user
	 * @return Response
	 */
	public function show(ShowUserRequest $request, User $user){
		return $this->userTransformer->transform($user);
	}

	/**
	 * Can't update most of the fields once they have been verified, only email/password/public.
	 * If they go to update their name(s), DOB or any other details that shouldn't change they need to resend us government issued Photo ID
	 * If they update their address we will need to reset their verified to until today, and check the governments database
	 *
	 * @param  User  $user
	 * @return Response
	 */
	public function edit(EditUserRequest $request, User $user){
		return $this->userTransformer->transform($user);
	}


	/**
	 * Can't update most of the fields once they have been verified, only email/password/pubilc.
	 *
	 * @param  User   		$user
	 * @return Response
	 */
	public function update(UpdateUserRequest $request, User $user){

		//If the user has the authentication level, they can change some things
		$user->update($request->except('token'));

	
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
	      	$user->avatar_id 	=	$file->id;
		}

		return $user;		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(DestroyUserRequest $request, User $user){

		if(Auth::user()->id != $user->id && !Auth::user()->can('delete-user')){
			abort(401,'You do not have permission to delete this user');
		}

	 	$user->delete(); //We want to leave the voting/comment record in tact as an anonomous vote
	 	return $user;
	}

}