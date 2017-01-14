<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use App\Vote;
use Illuminate\Support\Facades\Request;

class UserVoteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(User $user)
    {
        $limit = Request::get('limit') ?: 100;

        return Vote::where('user_id', $user->id)->paginate($limit);
    }
}
