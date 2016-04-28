<?php

namespace App\Http\Requests\Motion;

use App\Http\Requests\Request;
use Auth;

class StoreMotionRequest extends Request
{  
    


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->can('create-motion')){ 
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
            'title'             =>  'required|min:8|unique:motions,title',
            'status'            =>  'integer',
            'department_id'     =>  'required|integer|exists:departments,id',
            'closing'           =>  'date',
            'user_id'           =>  'integer|exists:users,id',
            'id'                =>  'integer'
        ];
    }

}
