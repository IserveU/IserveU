<?php

namespace App\Http\Requests\Community;

use App\Http\Requests\Request;

class UpdateCommunityRequest extends Request
{
    /**
     * The rules for all the variables.
     *
     * @var array
     */
    protected $rules = [
        'name'              => 'string|filled|unique:communities,name',
        'adjective'         => 'string|filled|unique:communities,adjective',
        'active'            => 'boolean',
        'slug'              => 'reject',
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $community = $this->route()->parameter('community');

        $this->rules['name'] = $this->rules['name'].','.$community->id;
        $this->rules['adjective'] = $this->rules['adjective'].','.$community->id;

        return $this->rules;
    }
}
