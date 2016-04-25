<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdateMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->can('administrate-motions')){ 
        //Is not the user who made it, or the site admin
            return true;
        }

        if($motion->expired){ //Motion has closed/expired
             return false;
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
        $motion = $this->route()->parameter('motion');

        return [
            'title'             =>  'min:8|unique:motions,title,'.$motion->id,
            'status'            =>  'integer',
            'department_id'     =>  'required|exists:departments,id',
            'closing'           =>  'date',
            'user_id'           =>  'required|integer|exists:users,id',
            'id'                =>  'integer'
        ];


    }
}
