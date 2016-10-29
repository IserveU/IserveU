<?php

namespace App\Http\Requests\User\UserRole;

use App\Http\Requests\Request;
use Auth;

class UpdateUserRoleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Auth::user()->can('administrate-permission')) {
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
            'role'  => 'reject',   // name in URL
            'user'  => 'reject',    // slug in URL
        ];
    }

    public function validate()
    {
        parent::validate();

        $role = $this->route()->parameter('role');
        $user = $this->route()->parameter('user');

        if ($user->hasRole($role->name)) {
            //TODO: Create a custom validator and hitch onto the validation method
            abort(400, "User already has the role ($role->name)");
        }
    }
}
