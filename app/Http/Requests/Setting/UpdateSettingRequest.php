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

        // done in the middleware
        return true;
    }

    public function rules()
    {
        //workaround to conver numeric value to int
        if (is_numeric($this['value'])) {
            $this['value'] = (int) $this['value'];
        }

        return $this->rules;
    }
}
