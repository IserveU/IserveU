<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class StoreMotionRequest extends Request
{  
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if(!Auth::user()->can('create-motion')){ 
            return false;
        }      

       //Can't change the status over 1 if not an admin
        $status  = $this->input('status');
        if(!Auth::user()->can('administrate-motion') && $status > 1){
            $this->attributes['status'] = 1;
            return false;
        }


        // if(isset($this->attributes['status']) && $status < $this->attributes['status']){
        //     abort(403,"You can not switch a status back");
        // }

        // switch ($status){
        //     case 0:
        //         $this->attributes['status'] = 0;
        //         break;
        //     case 1:
        //         $this->attributes['status'] = 1;
        //         break;
        //     case 2:
        //         if(Auth::check() && !Auth::user()->can('administrate-motion')){
        //             abort(401,"Unable to set user does not have permission to set motions as active");
        //         }
        //         if($value && !$this->motionRanks->isEmpty()){
        //             abort(403,"This motion has already been voted on, it cannot be reactivated after closing");
        //         }
                
        //         $this->attributes['status'] = 2;

        //         if(!$this->closing && $value == 1){
        //             $closing = new Carbon;
        //             $closing->addHours(Setting::get('motion.default_closing_time_delay',72));
        //             $this->closing = $closing;
        //         }
        //         break;
        //     case 3:
        //         $this->attributes['status'] = 3;
        //         break;
        // }
    


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
            'title'             =>  'required|min:8|unique:motions,title',
            'status'            =>  'integer',
            'department_id'     =>  'required|integer|exists:departments,id',
            'closing'           =>  'date',
            'user_id'           =>  'integer|exists:users,id',
            'id'                =>  'integer'
        ];
    }

}
