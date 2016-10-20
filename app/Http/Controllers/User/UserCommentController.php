<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Auth;

class UserCommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(User $user)
    {
        if ($user->publicallyVisible) {
            return $user->comments->sortBy('created_at');
        }

        if (!Auth::check()) {
            abort(401, 'Not allowed at the moment');
        }

        //Not the current user, or public and not an admin
        if (Auth::user()->id != $user->id && !Auth::user()->can('show-comment')) {
            abort(403, "You do not have permission to view this non-public user's comments");
        }

        return $user->comments->sortBy('created_at');
    }
}
