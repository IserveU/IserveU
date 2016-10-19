<?php

namespace App\Http\Controllers\User;

use App\CommentVote;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;

class UserCommentVoteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(User $user, Request $request)
    {
        $query = CommentVote::byUser($user->id);

        if ($request->has('motion_id')) {
            $query->onMotion($request->motion_id);
        }

        return $query->get();
    }
}
