<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;

class UpdateSettingRequest extends Request
{
    protected $rules = [
        'value' => 'required',
        'key'   => 'string',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        //workaround for beta message value.
        if (is_numeric($this['value'])) {
            $this['value'] = (int) $this['value'];
        }

        return $this->rules;
    }
}
