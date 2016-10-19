<?php

namespace App\Http\Controllers\Vote;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Vote\DestroyVoteRequest;
use App\Http\Requests\Vote\IndexVoteRequest;
use App\Http\Requests\Vote\ShowVoteRequest;
use App\Http\Requests\Vote\StoreUpdateVoteRequest;
use App\Vote;
use Auth;

class VoteController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(IndexVoteRequest $request)
    {
        return Vote::all();

        if (Auth::user()->can('view-vote')) { //Administrator able to see any vote
            return Vote::all();
        }

        return Vote::where('user_id', Auth::user()->id)->get(); //Get standard users comment votes
    }

    /**
     * Display the specified resource. User with decent permissions can see who posses other people votes, or you can see your own vote.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(ShowVoteRequest $request, Vote $vote)
    {
        if (Auth::user()->can('show-vote')) { //Is a person who can review votes
            return $vote;
        }

        if ($vote->user_id != Auth::user()->id) {        //This is not the person who cast the vote
            abort(401, 'You do not have permission to see this vote');
        }

        return $vote; //This person has no right to see this vote
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Vote $vote, StoreUpdateVoteRequest $request)
    {
        $vote->update([
            'position'    => $request->input('position'),
        ]);

        return $vote;
    }

    /**
     * You can't delete a vote, just switch to abstain.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(DestroyVoteRequest $request, Vote $vote)
    {
        $vote->position = 0;
        $vote->save();

        return $vote;
    }
}
