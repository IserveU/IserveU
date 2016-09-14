<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;
use Auth;

use App\Policies\CommentPolicy;
use App\Comment;

class UpdateCommentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        $comment = $this->route()->parameter('comment'); 

        if(Auth::user()->can('administrate-comment')){
            return true;
        }

        if(!$comment->vote->motion->motionOpenForVoting){
            return false; //Cant change your comments after the fact
        }

        if($comment->vote->user_id == Auth::user()->id){
            return true; //This users comment
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
            'text'          =>  'filled|string',
            'status'        =>  'valid_status|filled'
        ];

    }
}
