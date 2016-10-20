<?php

namespace App\Http\Controllers\CommentVote;

use App\Comment;
use App\CommentVote;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CommentVote\DestroyCommentVoteRequest;
use App\Http\Requests\CommentVote\IndexCommentVoteRequest;
use App\Http\Requests\CommentVote\ShowCommentVoteRequest;
use App\Http\Requests\CommentVote\UpdateCommentVoteRequest;
use App\Vote;
use Auth;

class CommentVoteController extends ApiController
{
    /**
     * Display a listing of the comment votes for this user.
     *
     * @return Response
     */
    public function index(IndexCommentVoteRequest $request)
    {
        if (Auth::user()->can('view-comment_vote')) { //Administrator able to see any comment vote
            return CommentVote::all();
        }

        return CommentVote::where('user_id', Auth::user()->id)->select('comment_id', 'position')->get(); //Get standard users comment votes
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(ShowCommentVoteRequest $request, CommentVote $commentVote)
    {
        return $commentVote;
    }

    /**
     * Update the specified resource in storage. Requires comment_vote_id as id.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateCommentVoteRequest $request, CommentVote $commentVote)
    {
        //Time to edit vote
        $commentVote->update($request->all());

        return $commentVote;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(DestroyCommentVoteRequest $request, CommentVote $commentVote)
    {
        $commentVote->forceDelete(); //There are no things relying on this and they might decide to create a new one

        return $commentVote;
    }
}
