<?php

namespace App\Http\Controllers\User;

use App\Filters\VoteFilter;
use App\Http\Controllers\ApiController;
use App\Http\Requests\User\Vote\IndexUserVoteRequest;
use App\User;
use App\Vote;

class UserVoteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(VoteFilter $filters, User $user, IndexUserVoteRequest $request)
    {
        $limit = $request->get('limit') ?: 100;

        return Vote::filter($filters)->paginate($limit);
    }
}
