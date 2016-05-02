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
         //Want to see unpublished motions
        if($this->has('status') && $this->input('status') < 2){
            if(!Auth::check() ){
                return false;
            }

            if(Auth::user()->can('show-motion')) return true;

            //If you want to see unpublshed motions you can only see yours
            $this->request->add(['user_id'=>Auth::user()->id]);
            return true;
        }

        //Not trying to see an unpublished motion
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
            'rank_greater_than' =>  'numeric',
            'rank_less_than'    =>  'numeric',
            'department_id'     =>  'numeric|exists:departments,id',
            'is_current'        =>  'boolean',
            'is_expired'        =>  'boolean',
            'newest'            =>  'boolean',
            'oldest'            =>  'boolean',
            'status'            =>  'integer',
            'user_id'           =>  'exists:users,id'
        ];
    }
}
