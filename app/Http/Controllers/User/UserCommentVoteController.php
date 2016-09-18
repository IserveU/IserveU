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
        $query = CommentVote::byUser($user->id);

        if($request->has('motion_id')){
            $query->onMotion($request->motion_id);
        }

        return $query->get();
    }

}
