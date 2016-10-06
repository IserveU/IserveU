<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\Request;
use Auth;


class StoreUpdatePageRequest extends Request
{  
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!Auth::check()){
            return false;
        }

        if(!Auth::user()->hasRole('administrator')){
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
            'title'             =>  'filled|string',
            'content'           =>  'string|filled',
            'slug'              =>  'reject'
        ];
    }

}
