<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class StoreUserRequest extends Request
{


    protected $rules = [
        'email'         =>  'email|required',
        'password'      =>  'required'
    ];


    /**
     * Anyone can create a user
     *
     * @return bool
     */
    public function authorize()
    {
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
