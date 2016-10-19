<?php

namespace App\Http\Requests\CommentVote;

use App\Http\Requests\Request;
use App\Vote;
use Auth;

class StoreCommentVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;


        if (!Auth::user()->can('create-comment_vote')) {
            return false; //You do not have permission to vote on a comment');
        }

        //Check logged in user has voted, and on this comment's motion
        $vote = Vote::where('vote_id', $this->input('vote_id'))->firstOrFail();

        //TODO: Stop comment voting on closed motions? Maybe.

        if (!$vote->user_id != Auth::user()->id) {
            return false; //Not this users vote
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment_id'    => 'reject',
            'position'      => 'integer|min:-1|max:1|required|filled',
            'vote_id'       => 'reject',
        ];
    }
}
