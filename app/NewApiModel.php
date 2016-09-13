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

    /**
     * Set by the skipVisibility() method
     */
    private $skipVisibility = false;

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
 


    public function removeVisible($value){
        if(is_array($value)){
            $this->visible = array_diff_key($this->visible,$value);

            return $this->visible;
        }

        if(array_key_exists($value,$this->visible)) unset($this->visible[$value]);
        return $this->visible;
    }

    /**
     * Makes sure that all attributes of this model are visible
     * Then sets the skipVisibility variable so they are not overridden
     * @return null
     */
    public function skipVisibility(){
       $this->setVisible(array_keys($this->attributes));
       $this->skipVisibility = true;
    }


    public function toArray(){
        if($this->skipVisibility){
            return parent::toArray();        
        }

        $this->setVisibility();
        return parent::toArray();        
    }

    public function toJson($options =0 ){
        if($this->skipVisibility){
            return parent::toJson($options);
        }


        $this->setVisibility();
        return parent::toJson($options);
    }


}
