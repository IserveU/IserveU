<?php

namespace App\Http\Controllers\Comment;

use App\Comment;
use App\CommentVote;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CommentVote\StoreCommentVoteRequest;
use App\Vote;
use Auth;

class CommentCommentVoteController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Comment $comment, StoreCommentVoteRequest $request)
    {
        $usersVote = Vote::where('user_id', Auth::user()->id)->where('motion_id', $comment->vote->motion_id)->first();

        $commentVote = CommentVote::updateOrCreate([
            'comment_id'   => $comment->id,
            'vote_id'      => $usersVote->id,
        ], [
            'comment_id'   => $comment->id,
            'vote_id'      => $usersVote->id,
            'position'     => $request->position,
        ]);

        return $commentVote;
    }
}
