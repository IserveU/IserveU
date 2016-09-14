<?php
namespace App\Http\Controllers\Motion;
use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Comment;
use App\CommentVote;
use App\Motion;
use \Cache;
use Setting;

class MotionCommentController extends ApiController
{
    /**
     * Display a listing of the motion's comments, this code could almost certainly be done better
     *
     * @return Response
     */
    public function index(Request $request, Motion $motion){
        
        $comments = [];

            
        $comments = Cache::tags(['motion.'.$motion->id])->remember('motion'.$motion->id.'_comments', 60, function() use ($motion){

            $comments['agreeComments'] =  Comment::whereHas('vote',function($q) use ($motion){
                $q->where('motion_id',$motion->id);

            })->position(1)->get()->sortByDesc('commentRank')->toArray();

            $comments['abstainComments'] =  Comment::whereHas('vote',function($q) use ($motion){
                $q->where('motion_id',$motion->id);

            })->position(0)->get()->sortByDesc('commentRank')->toArray();

            $comments['disagreeComments'] =  Comment::whereHas('vote',function($q) use ($motion){
                $q->where('motion_id',$motion->id);

            })->position(-1)->get()->sortByDesc('commentRank')->toArray();

               
            return $comments;
        });
        
        return $comments;

    }

}
