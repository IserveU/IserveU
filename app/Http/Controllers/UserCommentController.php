<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $comments = $user->comments->sortBy('created_at');
        return $comments;
    }

}
