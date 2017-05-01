<?php

namespace App\Http\Controllers\User;

use App\File;
use App\Filters\UserFilter;
use App\Http\Controllers\ApiController;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Transformers\UserTransformer;
use App\User;
use App\Vote;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserController extends ApiController
{
    protected $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->middleware('auth:api', ['except' => ['create', 'store']]);
    }

    /**
     * Display a listing of users who have set their profiles to be public. Public profiles can be defered to and their names show up
     * when they comment/vote on things.
     *
     * @return Response
     */
    public function index(UserFilter $filters, IndexUserRequest $request)
    {
        Log::info($request->input('roles'));
        $limit = $request->input('limit') ?: 20;

        return User::filter($filters)->paginate($limit);
    }

    /**
     * Store a newly created resource in storage, this will be created by the user (not an admin).
     *
     * @return Response
     */
    public function store(StoreUserRequest $request)
    {
        //Create a new user and fill secure fields
        $user = User::create($request->except('token'))->fresh();

        if (Auth::check()) {
            return $user->skipVisibility();
        }
        
        if (!Auth::check()) {
            Auth::setUser($user);
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @return Response
     */
    // need to have showuserrequest in future.
    public function show(Request $request, User $user)
    {
        return $user;
    }

    /**
     * Can't update most of the fields once they have been verified, only email/password/pubilc.
     *
     * @param User $user
     *
     * @return Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //If the user has the authentication level, they can change some things
        $user->update($request->except('token'));

        if ($request->file('government_identification')) {
            $file = new File();
            $file->uploadFile('government_identification', 'government_identification', $request);
            if (!$file->save()) {
                abort(403, $file->errors);
            }
            $user->government_identification_id = $file->id;
        }

        if ($request->file('avatar')) {
            $file = new File();
            $file->uploadFile('avatars', 'avatar', $request);
            if (!$file->save()) {
                abort(403, $file->errors);
            }
            $user->avatar_id = $file->id;
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete(); //We want to leave the voting/comment record in tact as an anonomous vote
        return $user;
    }

    /**
     * Alter record in setting JSON preferences field.
     *
     * @param User   $user User in question
     * @param string $key  Key value
     */
    public function setPreference(UpdateUserRequest $request, User $user, $key)
    {
        try {
            $user->setPreference($key, $request->input('value'));
        } catch (\Exception $e) {
            return response(['validation' => 'That preference does not exist. New preferences can not be set through the API'], 400);
        }

        $user->save();
    }
}
