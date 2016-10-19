<?php

namespace App\Http\Requests\Vote;

use App\Http\Requests\Request;
use App\Policies\VotePolicy;

class DestroyVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;

        return (new VotePolicy())->inputsAllowed($this->input(), $this->route()->parameter('vote'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
