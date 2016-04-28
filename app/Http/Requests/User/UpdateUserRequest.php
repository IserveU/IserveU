<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Auth;

class UpdateUserRequest extends Request
{

    protected $rules = [
     
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if(\Auth::user()->may('administrate-user')){ // Can administrate users anyway
            return true;
        }
        
        $user = $this->route()->parameter('user');

        if(Auth::user()->id == $user->id){  //Your own profile
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
        return $this->rules;
    }
}
