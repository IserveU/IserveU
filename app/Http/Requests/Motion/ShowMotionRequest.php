<?php

namespace App\Http\Requests\Motion;

use App\Http\Requests\Request;
use App\Vote;
use Auth;

class ShowMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $motion =  $this->route()->parameter('motion');

        if($motion->status < 2){
            if(!Auth::check()){
                return false;
            }

            if(Auth::user()->id == $motion->user_id){
                return true;
            }

            if(!Auth::user()->can('administrate-motion')){
                return false;
            }
        }

        if(Auth::check()){
            Vote::where('motion_id',$motion->id)->where('user_id',Auth::user()->id)->update(['visited'=>true]);
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
            //
        ];
    }
}
