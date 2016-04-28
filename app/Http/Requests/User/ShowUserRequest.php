<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Auth;

class ShowUserRequest extends Request
{
    /**
     * Display the user, but only if they are public or if the user logged in is this user (they are viewing/editing their own profile to see what it would look like if public)
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route()->parameter('user');

        if($user->public){
            return true;
        }
        
        if(\Auth::check()){
            if(\Auth::user()->may('show-user')){ // Can administrate users anyway
                return true;
            }

            if(Auth::user()->id == $user->id){  //Your own profile
                return true;
            }        
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
        return [
            //
        ];
    }
}
