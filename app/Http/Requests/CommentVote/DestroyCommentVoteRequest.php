<?php

namespace App\Http\Requests\CommentVote;

use App\Http\Requests\Request;
use Auth;

class DestroyCommentVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $commentVote = $this->route()->parameter('comment_vote');

        if(Auth::user()->can('delete-comment_vote')){
            return true;
        }

        if(Auth::user()->id == $commentVote->vote->user_id){
            return true;
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
        return [
            //
        ];
    }
}
