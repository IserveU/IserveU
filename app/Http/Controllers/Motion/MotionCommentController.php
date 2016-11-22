<?php

namespace App\Http\Controllers\Motion;

use App\Comment;
use App\Http\Controllers\ApiController;
use App\Motion;
use Cache;
use Illuminate\Http\Request;

class MotionCommentController extends ApiController
{
    /**
     * Display a listing of the motion's comments, this code could almost certainly be done better.
     *
     * @return Response
     */
    public function index(Request $request, Motion $motion)
    {
        $comments = [];


        $comments = Cache::tags(['motion.'.$motion->id])->remember('motion'.$motion->id.'_comments', 60, function () use ($motion) {
            $comments['agreeComments'] = Comment::whereHas('vote', function ($q) use ($motion) {
                $q->where('motion_id', $motion->id);
            })->position(1)->get()->toArray();


            $comments['abstainComments'] = Comment::whereHas('vote', function ($q) use ($motion) {
                $q->where('motion_id', $motion->id);
            })->position(0)->get()->toArray();

            $comments['disagreeComments'] = Comment::whereHas('vote', function ($q) use ($motion) {
                $q->where('motion_id', $motion->id);
            })->position(-1)->get()->toArray();


            return $comments;
        });

        return $comments;
    }
}
