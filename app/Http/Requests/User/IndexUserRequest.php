<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Auth;

class IndexUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Auth::user()->can('show-user')) {
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
        return [
            'orderBy.created_at'       => ['regex:(desc|asc)'],
            'orderBy.id'               => ['regex:(desc|asc)'],
            'status'                   => 'array',
            'identityVerified'         => 'boolean',
            'addressVerified'          => 'boolean',
            'lastName'                 => 'string',
            'middleName'               => 'string',
            'firstName'                => 'string',
            'allNames'                 => 'string',
            'page'                     => 'integer',
            'limit'                    => 'integer',
            'roles'                    => 'array',
         ];
    }
}
