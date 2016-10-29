<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\UserRole\DestroyUserRoleRequest;
use App\Http\Requests\User\UserRole\UpdateUserRoleRequest;
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRoleRequest $request, User $user, Role $role)
    {
        $user->addRole($role);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyUserRoleRequest $request, User $user, Role $role)
    {
        $user->removeRole($role);

        return $user;
    }
}
