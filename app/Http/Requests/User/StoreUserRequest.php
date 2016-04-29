<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class StoreUserRequest extends Request
{


    protected $rules = [
        'email'                     =>  'email|required',
        'password'                  =>  'required',
        'first_name'                =>  'required|string',
        'last_name'                 =>  'required|string',
        'address_verified_until'    =>  'date|before:+2000 days|after:today'
    ];


    /**
     * Anyone can create a user
     *
     * @return bool
     */
    public function authorize()
    {

        if(Auth::check() && \Auth::user()->can('administrate-user')){ // Can administrate users anyway
            return true;
        }

        if($this->input('identity_verified')){
            return false;
        }
        
        if($this->input('address_verified_until')){
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
        return $this->rules;
    }
}
