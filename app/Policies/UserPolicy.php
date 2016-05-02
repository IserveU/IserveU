<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

use Auth;

class UserPolicy
{
    use HandlesAuthorization;
   
    public function getVisible(User $user){

        
        if(Auth::check()){
            if(Auth::user()->can("show-user")){ //Admin
                return $user->setVisible(['first_name','last_name','middle_name','email','ethnic_origin_id','date_of_birth','public','id','login_attempts','created_at','updated_at','identity_verified', 'permissions', 'user_role', 'votes','address_verified_until','government_identification','need_identification','avatar', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted', 'community_id']);
            }

            if($user->id == Auth::user()->id){ //The person who created this
                return $user->setVisible(['first_name','last_name','middle_name','email','ethnic_origin_id','date_of_birth','public','id','permissions','votes','address_verified_until','need_identification','avatar', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted', 'community_id']);   
            }
        }

        if($user->public){
            return $user->setVisible(['first_name','last_name','public','id','votes','totalDelegationsTo','avatar']);
        }

        return $user->setVisible(['id']); //Private user
    }


}
