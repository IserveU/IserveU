<?php

namespace App\Http\Requests\Vote;

use App\Http\Requests\Request;
use Auth;

use App\Policies\VotePolicy;


class StoreVoteRequest extends Request
{  
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (new VotePolicy())->inputsAllowed($this->input(),$this->route()->parameter('vote'));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'motion_id'     =>  'integer|required|exists:motions,id|unique_with:votes,user_id',
            'position'      =>  'integer|min:-1|max:1',
            'user_id'       =>  'integer|required|exists:users,id'
        ];
    }

}
