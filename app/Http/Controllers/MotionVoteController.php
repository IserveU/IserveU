<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MotionVoteController  extends ApiController{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($motion)
    {

        return $motion->votes()->agree()->active()->count();

                // Probably good for the votes function to profile who has what share
        // $votes = $motion->votes->groupBy('deferred_to_id')->toArray();
        // $councillorIds = array_column(User::councillor()->get()->toArray(),'id','id');  
        // $deferredToCouncilor = array_intersect_key($votes,array_flip($councillorIds));


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
}
