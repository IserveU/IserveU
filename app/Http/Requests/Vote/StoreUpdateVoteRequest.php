<?php

namespace App\Http\Requests\Vote;

use App\Http\Requests\Request;
use App\Policies\VotePolicy;

class StoreUpdateVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $vote = $this->route()->parameter('vote');

        $motion = $this->route()->parameter('motion');

        if (!$motion) {
            $motion = $vote->motion;
        }

        return (new VotePolicy())->inputsAllowed($this->input(), $motion, $vote);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'motion_id' => 'reject',
            'position'  => 'integer|min:-1|max:1|required|filled',
            'user_id'   => 'reject',
        ];
    }
}
