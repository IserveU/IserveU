<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Auth;

class UpdateUserRequest extends Request
{

    /**
     * The rules for all the variables
     * @var array
     */
    protected $rules = [    
        'email'                     =>  'email|unique:users,email',
        'password'                  =>  'min:8',
        'first_name'                =>  'string',
        'middle_name'               =>  'string',
        'last_name'                 =>  'string',
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
        'preferences'               =>  'json'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if(\Auth::user()->can('administrate-user')){ // Can administrate users anyway
            return true;
        }

        if($this->input('identity_verified')){
            return false;
        }
        
        if($this->input('address_verified_until')){
            return false;
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
        $user = $this->route()->parameter('user');

        $this->rules['email']             = $this->rules['email'].",".$user->id;
        $this->rules['remember_token']    = $this->rules['email'].",".$user->id;

        return $this->rules;
    }
}
