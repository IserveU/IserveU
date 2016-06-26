<?php

namespace App\Http\Requests\Vote;

use App\Http\Requests\Request;
use App\Vote;
use Auth;

class ShowVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->can('show-vote')) return true;

        $vote =  $this->route()->parameter('vote');

        if($vote->user_id == Auth::user()->id) return true;
            
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
