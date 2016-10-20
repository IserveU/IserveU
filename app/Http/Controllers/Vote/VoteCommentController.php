<?php

namespace App\Http\Controllers\Vote;

use App\Comment;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Vote;

class VoteCommentController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Vote $vote, StoreCommentRequest $request)
    {
        $comment = Comment::updateOrCreate([
            'vote_id'   => $vote->id,
        ],
              $request->all()
        );

        return $comment;
    }
}
