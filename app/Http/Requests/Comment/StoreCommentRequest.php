<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;
use Auth;

use App\Policies\CommentPolicy;
use App\Vote;

class StoreCommentRequest extends Request
{  
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!Auth::user()->can('create-comment')){
            return false;
            //abort(401,'You do not have permission to write a comment');
        }

        $vote = Vote::findOrFail(Request::get('vote_id'));
    
        if(!$vote->motion->motionOpenForVoting){
            return false; //Cant comment on closed motions
        }

        if($vote->user_id != Auth::user()->id){
            return false; //You can not comment tied to another users vote
        }

        return true;


    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text'          =>  'min:3|string',
            'vote_id'       =>  'integer|exists:votes,id|unique:comments,vote_id',
            'id'            =>  'integer'
        ];
    }

}
