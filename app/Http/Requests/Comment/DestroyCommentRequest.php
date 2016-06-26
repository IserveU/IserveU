<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;
use Auth;

class DestroyCommentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $comment = $this->route()->parameter('comment');

        if(Auth::user()->can('delete-comment')){
            return true;
        }

        if($comment->user->id == Auth::user()->id){
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
