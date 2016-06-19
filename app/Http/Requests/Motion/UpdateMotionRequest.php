<?php

namespace App\Http\Requests\Motion;

use App\Http\Requests\Request;
use Auth;

use App\Policies\MotionPolicy;

class UpdateMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {        
     //  $motion = $this->route()->parameter('motion');

       return (new MotionPolicy())->inputsAllowed($this->input(),$this->route()->parameter('motion'));


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
            'department_id'     =>  'exists:departments,id',
            'closing'           =>  'date',
            'user_id'           =>  'integer|exists:users,id',
            'id'                =>  'integer'
        ];


    }
}
