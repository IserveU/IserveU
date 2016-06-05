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
        if(!Auth::user()->can('create-vote')){
            return false;
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
            'motion_id'     =>  'integer|required|exists:motions,id|unique_with:votes,user_id',
            'position'      =>  'integer|digits_between:-1,1',
            'user_id'       =>  'integer|required|exists:users,id'
        ];
    }

}
