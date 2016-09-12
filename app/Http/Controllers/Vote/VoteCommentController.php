<?php
namespace App\Http\Controllers\Vote;
use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use App\Vote;
use App\Comment;
use App\Http\Requests\Comment\StoreCommentRequest;


class VoteCommentController  extends ApiController{
 


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Vote $vote, StoreCommentRequest $request)
    {   

        $comment  = Comment::updateOrCreate([
            'vote_id'   =>  $vote->id
        ],[
            'vote_id'   =>  $vote->id,
            'text'      =>  $request->text,
            'status'    =>  $request->status
        ]);
        return $comment;
    }
}
