<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiController;

use Illuminate\Support\Facades\Request;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\User;
use App\Vote;
use DB;

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

        if (Auth::user()->id != $user->id && !$user->public && !Auth::user()->can('show-vote')) { //Not the current user, or public and not an admin
             abort(401,"You do not have permission to view this non-public user's votes");
        }

        $votes = Vote::where('user_id',$user->id)->paginate($limit);

        return $votes;
    }

}