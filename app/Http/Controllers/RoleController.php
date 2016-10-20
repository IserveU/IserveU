<?php

namespace App\Http\Controllers;

use App\Role;

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
}
