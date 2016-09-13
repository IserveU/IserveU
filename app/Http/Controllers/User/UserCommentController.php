<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class UserCommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(User $user)
    {   
        if((Auth::user()->id != $user->id && !$user->public) && !Auth::user()->can('show-comment')) { //Not the current user, or public and not an admin
             abort(403,"You do not have permission to view this non-public user's comments");
        }

        $comments = $user->comments->sortBy('created_at');
        
        return $comments;
    }

}
