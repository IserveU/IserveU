<?php

namespace App\Http\Requests\CommentVote;

use App\Http\Requests\Request;
use Auth;

use App\Policies\CommentVotePolicy;

class UpdateCommentVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        $commentVote = $this->route()->parameter('comment_vote');
        
        if(Auth::user()->can('administrate-commentVote')){
            return true;
        }

         if(!Auth::user()->can('create-comment_vote')){
            return false; //abort(401,'You do not have permission to vote on a comment');
        }

        if(!$commentVote->vote->motion->motionOpenForVoting){
            return false; //Cant change your commentVotes after the fact
        }

        if($commentVote->vote->user_id == Auth::user()->id){
            return true; //This users commentVote
        }

        return false;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $commentVote = $this->route()->parameter('commentVote');

        return [
            'text'          =>  'min:3|string',
            'id'            =>  'integer'
        ];

    }
}
