<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Request;

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


}
