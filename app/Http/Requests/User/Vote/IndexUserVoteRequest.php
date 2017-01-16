<?php

namespace App\Http\Requests\User\Vote;

use App\Http\Requests\Request;
use Auth;

class IndexUserVoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route()->parameter('user');

        if ($user->publiclyVisible) {
            return true;
        }

        if (!Auth::check()) {
            return false;
        }

        if (Auth::user()->can('show-vote')) { //Not the current user, or public and not an admin
        return true;
        }

        if (Auth::user()->id == $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
