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
        'email'                     =>  'email|unique:users,email|min:0',
        'password'                  =>  'min:8',
        'first_name'                =>  'string|filled',
        'last_name'                 =>  'string|filled',
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
        'agreement_accepted'        =>  'boolean',
        'preferences'               =>  'json',
        'community_id'              =>  'exists:communities,id'
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
