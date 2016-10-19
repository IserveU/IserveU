<?php

namespace App\Http\Controllers;

use App\EthnicOrigin;



class EthnicOriginController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return EthnicOrigin::all();
    }
}
