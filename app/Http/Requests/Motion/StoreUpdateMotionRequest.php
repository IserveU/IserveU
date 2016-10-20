<?php

namespace App\Http\Requests\Motion;

use App\Http\Requests\Request;
use App\Policies\MotionPolicy;

class StoreUpdateMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (new MotionPolicy())->inputsAllowed(
                    $this->all(),
                    $this->route()->parameter('motion')
                );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'filled|min:1,title|string',
            'summary'           => 'string',
            'text'              => 'nullable',
            'status'            => 'string|valid_status',
            'department_id'     => 'integer|exists:departments,id',
            'closing_at'        => 'date|after:today',
            'user_id'           => 'integer|exists:users,id',
            'id'                => 'integer',
        ];
    }
}
