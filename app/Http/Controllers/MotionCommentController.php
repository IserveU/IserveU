<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Comment;
use App\CommentVote;

class MotionCommentController extends ApiController
{
    /**
     * Display a listing of the motion's comments, this code could almost certainly be done better
     *
     * @return Response
     */
    public function index($motion){
        

        $comments = array();

        if(Auth::user()->can('view-comments')){ //A full admin who can see whatever
            $comments['agreeComments']      = Comment::with('vote.user','commentVotes')->where('motion_id',$motion->id)->agree()->get()->sortByDesc('commentRank')->toArray();
            $comments['disagreeComments']   =   Comment::with('vote.user','commentVotes')->where('motion_id',$motion->id)->disagree()->get()->sortByDesc('commentRank')->toArray();
        } else { //Load the standard cached comments for the page
            $comments = Cache::remember('motion'.$motion->id.'_comments', config('app.cachetime'), function() use ($motion){
                $comments['agreeComments']      = Comment::with('vote.user','commentVotes')->where('motion_id',$motion->id)->agree()->get()->sortByDesc('commentRank')->toArray();
                $comments['disagreeComments']   =   Comment::with('vote.user','commentVotes')->where('motion_id',$motion->id)->disagree()->get()->sortByDesc('commentRank')->toArray();
                return $comments;
            });
        }

        $comments['thisUsersComment'] = Comment::where('motion_id',$motion->id)->with('vote')->where('user_id',Auth::user()->id)->first();
        $comments['thisUsersCommentVotes'] = CommentVote::where('motion_id',$motion->id)->where('user_id',Auth::user()->id)->get();

        return $comments;
        
    }

}
