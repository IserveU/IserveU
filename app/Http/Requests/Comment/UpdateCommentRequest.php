<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;
use Auth;

use App\Policies\CommentPolicy;

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

        if($comment->user->id == Auth::user()->id){
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
        $comment = $this->route()->parameter('comment');

        return [
            'text'          =>  'min:3|string',
            'id'            =>  'integer'
        ];

    }
}