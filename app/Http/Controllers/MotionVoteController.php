<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Vote;

class MotionVoteController  extends ApiController{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($motion)
    {

        $passiveVotes = Vote::where('motion_id',$motion->id)->cast()->passive()->get()->groupBy('deferred_to_id');

        $votesCount = array();
        $totalVotes = Vote::where('motion_id',$motion->id)->cast()->count();

        foreach($passiveVotes as $id => $vote){
            $count = count($vote);
            $votesCount[$vote[0]->position]['passive']['number'] = $count;
            $votesCount[$vote[0]->position]['passive']['percent'] = floor(($count/$totalVotes)*100)/100;
        }

        $activeVotes = Vote::where('motion_id',$motion->id)->cast()->active()->get()->groupBy('position');

        foreach($activeVotes as $id => $vote){
            $count = count($vote);
            $votesCount[strval($id)]['active']['number'] = $count;
            $votesCount[strval($id)]['active']['percent'] = floor(($count/$totalVotes)*100);
        }
        
        $votesCount[2] = "empty"; // this is a quick-fix for typecast error


        return $votesCount;
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
