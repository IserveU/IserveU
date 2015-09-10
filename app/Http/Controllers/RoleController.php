<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use Zizaco\Entrust\Entrust;
use App\Role;
use App\User;

class RoleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    /*
    *   @params user_id     The ID of the user that you want to grant the permission to
    *   @params role_name   The string name of the role
    */
    public function grant(){
        if(!Auth::user()->can('administrate-permissions')){
            abort(401,"You can not edit user permissions");
        }

        $user_id = Request::get('user_id');
        $role_name = Request::get('role_name');

        if(!is_numeric($user_id)){
            abort(403,"User id needs to be an integer");
        }

        $user = User::find($user_id);
        if(!$user){
            abort(403,"User with the id of ($user_id) not found");
        }

        if($user->hasRole($role_name)){
            abort(403,"User already has the role ($role_name)");

        }

        $user->addUserRoleByName($role_name);

        return $user;
    }

}
