<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use Auth;

use App\Http\Requests\Motion\CreateMotionRequest;
use App\Http\Requests\Motion\UpdateMotionRequest;

use App\Motion;

class MotionPolicy
{
    use HandlesAuthorization;


    public function inputsAllowed(array $inputs, Motion $motion = null){

        if($motion){ //And update
            if($motion->expired){ //Motion has closed/expired
                 return false;
            }


         //   if(!$this->closing && $value == 1){
        //             $closing = new Carbon;
        //             $closing->addHours(Setting::get('motion.default_closing_time_delay',72));
        //             $this->closing = $closing;
        //         }

        }

        if(!Auth::user()->can('create-motion')){
            return false;
        }     
   
        //Can't change the status over 1 if not an admin
        if(array_key_exists("status",$inputs)){
            if(!Auth::user()->can('administrate-motion') && $inputs['status'] > 1){
                return false;
            }
        }


        //Cant set another user as the creator of a motion if you're just a regular citizen
        if(array_key_exists("user_id",$inputs)){
            if(!Auth::user()->can('administrate-motion') && Auth::user()->id != $inputs['user_id']){
                return false;
            }
        }

      
        //         if($value && !$this->motionRanks->isEmpty()){
        //             abort(403,"This motion has already been voted on, it cannot be reactivated after closing");
        //         }
       
   
        //        
       return true;

    }

    //TODO
    public function getVisible(Motion $motion){

        
        if(Auth::check()){
            if(Auth::user()->can("show-motion")){ //Admin
                return $user->setVisible([]);
            }

            if($motion->user_id == Auth::user()->id){ //The person who created this
                return $user->setVisible([]);   
            }
        }

        if($motion->motionOpenForVoting){
            return $user->setVisible([]);
        }

        return $user->setVisible(['id']); //Private user


    }
}
