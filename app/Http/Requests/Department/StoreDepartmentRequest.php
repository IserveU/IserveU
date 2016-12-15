<?php

namespace App\Http\Requests\Department;

use App\Http\Requests\Request;

class StoreDepartmentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
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
        return [
            'name'              => 'required|string|filled|unique:departments,name',
            'icon'              => 'string|filled',
            'slug'              => 'reject',
            'active'            => 'boolean',
        ];
    }
}
