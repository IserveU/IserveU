<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use Auth;

use App\Motion;
use App\Vote;

class VotePolicy
{
    use HandlesAuthorization;


    public function inputsAllowed(array $inputs, Vote $vote = null){
        
        if(!Auth::user()->can('create-vote')){
            return false;
        }    

        if($vote){ //An update
            $motion = $vote->motion;
        } else {
           if(!array_key_exists('motion_id',$inputs)) { //Had to put this here because form requests do auth first
                abort(403,'motion_id field required to create a new vote');
            }
            $motion = Motion::findOrFail($inputs['motion_id']);
        }

        if(!$motion->motionOpenForVoting){ //Motion has closed/expired

             return false;
        }

        if(array_key_exists('user_id',$inputs) && $inputs['user_id']!=Auth::user()->id) { 
            // Can only vote/alter own
            return false;
        }

        return true;

    }

    //TODO
    public function getVisible(Vote $vote){

        if(Auth::check()){
            if(Auth::user()->can("show-vote")){ //Admin
                return $user->setVisible(['user_id','motion_id','position']);
            }

            if($vote->user_id == Auth::user()->id){ //The person who created this
                return $user->setVisible(['user_id','motion_id','position']);   
            }
        }

        return $user->setVisible(['motion_id','position']); //Private user
    }
}
