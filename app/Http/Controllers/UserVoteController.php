<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\User;

class UserVoteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($user)
    {
        if ((Auth::user()->id != $user->id || !$user->public )  && !Auth::user()->can('show-votes')) { //Not the current user, or public and not an admin
             abort(401,"You do not have permission to view this non-public user's votes");
        }

        $votes = User::with('votes.motion')->find($user->id);
        
        return $votes;
    }

}