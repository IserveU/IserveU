<?php

namespace App\Http\Requests\Motion;

use App\Http\Requests\Request;
use App\Vote;
use Auth;

class IndexMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if(Auth::check() ){
            // Admin view permission
            if(Auth::user()->can('show-motion')) return true;

            //See your own motion
            if($this->has('user_id') && Auth::user()->id == $this->input('user_id')){
                return true;
            }  
        }

        //Want to see published motions
        if($this->input('status') >= 2){
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
            'rank_greater_than' =>  'numeric',
            'rank_less_than'    =>  'numeric',
            'department_id'     =>  'numeric|exists:departments,id',
            'is_current'        =>  'boolean',
            'is_expired'        =>  'boolean',
            'newest'            =>  'boolean',
            'oldest'            =>  'boolean',
            'status'            =>  'integer|required',
            'user_id'           =>  'exists:users,id'
        ];
    }
}
