<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\CommentVote;

class UserCommentVoteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(User $user, Request $request)
    {   
        if(!$request->has('motion_id')){
            return $user->comment_votes; //All comment votes
        }

       // dd($request->motion_id);
        $commentVotes = CommentVote::byUser($user->id)->onMotion($request->motion_id)->get();

        dd($commentVotes);

    }

}
