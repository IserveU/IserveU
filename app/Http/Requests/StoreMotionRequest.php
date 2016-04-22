<?php

namespace App\Http\Requests;

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
        if(Auth::user()->can('create-motions')){ 
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
        // todo rethink these rules
        return [
            'title'             =>  'min:8|unique:motions,title',
            'status'            =>  'integer',
            'department_id'     =>  'exists:departments,id',
            'closing'           =>  'date',
            'text'              =>  'min:10',
            'user_id'           =>  'integer|exists:users,id',
            'id'                =>  'integer'
        ];
    }
}
