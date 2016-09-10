<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Auth;

class StoreUserRequest extends Request
{


    protected $rules = [
        'email'                     =>  'email|required|unique:users,email|min:0',
        'password'                  =>  'required|min:8',
        'first_name'                =>  'required|string|filled',
        'last_name'                 =>  'required|string|filled',
        'middle_name'               =>  'string|filled',
        'ethnic_origin_id'          =>  'integer|exists:ethnic_origins,id',
        'date_of_birth'             =>  'date|before:today',
        'status'                    =>  'string|valid_status',
        'login_attempts'            =>  'integer',
        'identity_verified'         =>  'boolean',
        'remember_token'            =>  'unique:users,remember_token',
        'postal_code'               =>  'string|max:10',
        'street_name'               =>  'string',
        'street_number'             =>  'integer',
        'unit_number'               =>  'string',
        'address_verified_until'    =>  'date|after:today|before:+2000 days',
        'agreement_accepted'        =>  'date|after:now',
        'preferences'               =>  'json',
        'community_id'              =>  'exists:communities,id'
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
