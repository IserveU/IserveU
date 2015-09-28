<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserCommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($user)
    {   
        if((Auth::user()->id != $user->id && !$user->public) && !Auth::user()->can('show-comments')) { //Not the current user, or public and not an admin
             abort(401,"You do not have permission to view this non-public user's comments");
        }

        $comments = $user->comments->sortBy('created_at');
        
        return $comments;
    }

}
