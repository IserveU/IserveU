<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Auth;

class StoreUserRequest extends Request
{


    protected $rules = [
        'email'                     =>  'email|required|unique:users,email',
        'password'                  =>  'required',
        'first_name'                =>  'required|string',
        'last_name'                 =>  'required|string',
        'middle_name'               =>  'string',
        'ethnic_origin_id'          =>  'integer|exists:ethnic_origins,id',
        'date_of_birth'             =>  'date',
        'public'                    =>  'boolean',
        'login_attempts'            =>  'integer',
        'identity_verified'         =>  'boolean',
        'remember_token'            =>  'unique:users,remember_token',
        'postal_code'               =>  'string',
        'street_name'               =>  'string',
        'street_number'             =>  'integer',
        'unit_number'               =>  'integer',
        'address_verified_until'    =>  'date|before:+2000 days|after:today',
        'agreement_accepted'        =>  'boolean',
        'preferences'               =>  'array'
    ];


    /**
     * Anyone can create a user
     *
     * @return bool
     */
    public function authorize()
    {

        if(Auth::check() && Auth::user()->can('administrate-user')){ // Can administrate users anyway
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
