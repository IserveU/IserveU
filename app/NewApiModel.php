<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Role;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

use Carbon\Carbon;

//While migrating things off the old one
class NewApiModel extends Model
{

    protected $skipVisibility = false;

    public function getCreatedAtAttribute($attr) {        
        $carbon = Carbon::parse($attr);

        return array(
            'diff'          =>      $carbon->diffForHumans(),
            'alpha_date'    =>      $carbon->format('j F Y'),
            'carbon'        =>      $carbon
        );
    }

    public function getUpdatedAtAttribute($attr) {        
        $carbon = Carbon::parse($attr);

        return array(
            'diff'          =>      $carbon->diffForHumans(),
            'alpha_date'    =>      $carbon->format('j F Y'),
            'carbon'      =>        $carbon
        );
    }
 

    public function skipVisibility(){
        $this->skipVisibility = true;
        //Figure out how to loop all attributes $this->attributes->getkeys();
        $this->setVisible(['email','ethnic_origin_id','password','first_name','middle_name','last_name','date_of_birth','public','website', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted', 'community_id','identity_verified', 'address_verified_until','preferences']);

        return $this;
    }


    public function toArray(){
        if(!$this->skipVisibility){
            $this->setVisibility();
        }

        return parent::toArray();        
    }

    public function toJson($options =0 ){     
        if(!$this->skipVisibility){
            $this->setVisibility();
        }

        return parent::toJson($options);
    }



}
