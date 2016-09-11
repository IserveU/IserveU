<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Request;

use Zizaco\Entrust\Entrust;
use App\Role;
use App\User;

class UserRoleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return $user->roles;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
       if(!Auth::user()->can('administrate-permission')){
            abort(401,"You can not edit user permissions");
        }

        $role_name = Request::get('role_name');

        $user = User::find($user->id);
        if(!$user){
            abort(403,"User with the id of ($user->id) not found");
        }

        if($user->hasRole($role_name)){
            abort(403,"User already has the role ($role_name)");
        }

        $user->addUserRoleByName($role_name);

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, $role_id)
    {
        if(!Auth::user()->can('administrate-permission')){
            abort(401,"You can not edit user permissions");
        }

        $role = Role::where('id', '=', $role_id)->firstOrFail();

        if(!$user->hasRole($role->name)){
            abort(403,"User doesn't have the role id of ($role_id)");
        }

        $user->removeUserRole($role_id);

        return $user;
    }
}
