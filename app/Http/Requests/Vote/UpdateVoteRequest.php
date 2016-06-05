<?php

namespace App\Http\Requests\Vote;

use App\Http\Requests\Request;
use Auth;

use App\Policies\VotePolicy;

class UpdateVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {        
     //  $vote = $this->route()->parameter('vote');

       return (new VotePolicy())->inputsAllowed($this->input(),$this->route()->parameter('vote'));





    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $vote = $this->route()->parameter('vote');

        return [
            'motion_id'     =>  'integer|exists:motions,id|unique_with:votes,user_id,'.$vote->id,,
            'position'      =>  'integer|digits_between:-1,1',
            'user_id'       =>  'integer|exists:users,id',
            'id'            =>  'integer'
        ];


    }
}
