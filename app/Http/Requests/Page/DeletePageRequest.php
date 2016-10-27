<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\Request;
use Auth;

class DeletePageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $page = $this->route()->parameter('page'); //TO GET RESOURCE ROUTING WORKING

        //Can not remove home page, as site files are tied to it
        if($page->id == 1){
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
        
        ];
    }
}
