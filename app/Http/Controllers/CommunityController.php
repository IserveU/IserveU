<?php

namespace App\Http\Controllers;

use App\Community;

class CommunityController extends ApiController
{
    public function __construct()
    {
        $this->middleware('role:administrator', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Community::all();
    }
}
