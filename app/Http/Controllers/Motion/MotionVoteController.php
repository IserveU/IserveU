<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Vote\StoreUpdateVoteRequest;
use App\Motion;
use App\Vote;
use Auth;

class MotionVoteController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Motion $motion)
    {

      //  return $motion->votes;

        $passiveVotes = Vote::where('motion_id', $motion->id)->cast()->passive()->get()->groupBy('deferred_to_id');

        $votesCount = [];
        $totalVotes = Vote::where('motion_id', $motion->id)->cast()->count();

        foreach ($passiveVotes as $id => $vote) {
            $count = count($vote);
            $votesCount[$vote[0]->positionHumanReadable]['passive']['number'] = $count;
            $votesCount[$vote[0]->positionHumanReadable]['passive']['percent'] = floor(($count / $totalVotes) * 100);
        }

        $activeVotes = Vote::where('motion_id', $motion->id)->cast()->active()->get()->groupBy('position');

        foreach ($activeVotes as $id => $vote) {
            $count = count($vote);

            $votesCount[$vote->first()->positionHumanReadable]['active']['number'] = $count;
            $votesCount[$vote->first()->positionHumanReadable]['active']['percent'] = floor(($count / $totalVotes) * 100);
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
        $vote = Vote::updateOrCreate([
            'motion_id' => $motion->id,
            'user_id'   => Auth::user()->id,
        ], [
            'motion_id' => $motion->id,
            'user_id'   => Auth::user()->id,
            'position'  => $request->input('position'),
        ]);

        return $vote;
    }
}
