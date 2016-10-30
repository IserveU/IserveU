<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Setting;
use Auth;

class StoreUserRequest extends Request
{
    protected $rules = [
        'email'                         => 'email|required|unique:users,email|min:0',
        'password'                      => 'required|min:8',
        'first_name'                    => 'required|string|filled',
        'last_name'                     => 'required|string|filled',
        'middle_name'                   => 'string|filled',
        'ethnic_origin_id'              => 'integer|exists:ethnic_origins,id',
        'date_of_birth'                 => 'date|before:today',
        'status'                        => 'string|valid_status',
        'login_attempts'                => 'integer',
        'identity_verified'             => 'boolean',
        'remember_token'                => 'unique:users,remember_token',
        'postal_code'                   => 'string|max:10',
        'phone'                         => 'numeric|digits_between:8,15|unique:users,phone',
        'street_name'                   => 'string',
        'street_number'                 => 'integer',
        'unit_number'                   => 'string',
        'address_verified_until'        => 'date|after:today|before:+2000 days',
        'government_identification_id'  => 'exists|files,id',
        'agreement_accepted'            => 'boolean',
        'preferences'                   => 'json',
        'community_id'                  => 'exists:communities,id',
    ];

    /**
     * Anyone can create a user.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check() && Auth::user()->can('administrate-user')) { // Can administrate users anyway
            return true;
        }

        if ($this->input('identity_verified')) {
            return false;
        }

        if ($this->input('address_verified_until')) {
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
        $rules = [];

        //If birthday is required in create
        if (Setting::get('security.ask_for_birthday_on_create', 0)) {
            $this->rules['date_of_birth'] = $this->rules['date_of_birth'].'|required';
        }

        return array_merge($rules, $this->rules);
    }
}
