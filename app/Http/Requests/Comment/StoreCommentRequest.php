<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;
use App\Vote;
use Auth;

class StoreCommentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Auth::user()->can('create-comment')) {
            return false;
            //abort(401,'You do not have permission to write a comment');
        }

        $vote = $this->route()->parameter('vote');

        if (!$vote->motion->motionOpenForVoting) {
            return false; //Cant comment on closed motions
        }

        if ($vote->user_id != Auth::user()->id) {
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
            'text'          => 'filled|required|string',
            'vote_id'       => 'reject',
            'id'            => 'reject',
            'status'        => 'valid_status|filled',
        ];
    }
}
