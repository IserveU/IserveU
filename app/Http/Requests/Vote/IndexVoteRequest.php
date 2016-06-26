<?php

namespace App\Http\Requests\Vote;

use App\Http\Requests\Request;
use App\Vote;
use Auth;

class IndexVoteRequest extends Request
{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Security is in the controller
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
    }
}
