<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use App\Vote;
use App\Motion;
use App\Http\Requests\Vote\StoreUpdateVoteRequest;

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
            $votesCount[$vote[0]->position]['passive']['percent'] = floor(($count/$totalVotes)*100);
        }

        $activeVotes = Vote::where('motion_id',$motion->id)->cast()->active()->get()->groupBy('position');

        foreach($activeVotes as $id => $vote){
            $count = count($vote);
            $votesCount[strval($id)]['active']['number'] = $count;
            $votesCount[strval($id)]['active']['percent'] = floor(($count/$totalVotes)*100);
        }
       
        return $votesCount;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Motion $motion, StoreUpdateVoteRequest $request)
    {   

        $vote  = Vote::updateOrCreate([
            'motion_id' =>  $motion->id,
            'user_id'   =>  Auth::user()->id            
        ],[
            'motion_id' =>  $motion->id,
            'user_id'   =>  Auth::user()->id,
            'position'  =>  $request->input('position')
        ]);

        return $vote;
    }
}
