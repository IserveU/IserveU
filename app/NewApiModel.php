<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Role;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

use Carbon\Carbon;

class NewApiModel extends Model
{

    /**
     * Set by the skipVisibility() method
     * @var boolean
     */
    private $skipVisibility = false;


    /**
     * Converts the date/time to a handy set of date details
     * @param  String $attr String of date to be parsed
     * @return Array
     */
    public function getCreatedAtAttribute($attr) {        
        $carbon = Carbon::parse($attr);

        return array(
            'diff'          =>      $carbon->diffForHumans(),
            'alpha_date'    =>      $carbon->format('j F Y'),
            'carbon'        =>      $carbon
        );
    }

    /**
     * Converts the date/time to a handy set of date details
     * @param  String $attr String of date to be parsed
     * @return Array
     */
    public function getUpdatedAtAttribute($attr) {        
        $carbon = Carbon::parse($attr);

        return array(
            'diff'          =>      $carbon->diffForHumans(),
            'alpha_date'    =>      $carbon->format('j F Y'),
            'carbon'      =>        $carbon
        );
    }
 

    /**
     * Removes elements from the visible array
     * @param  Array An array or string to take out of the visible array
     * @return Array The current visible array
     */
    public function removeVisible($value){
        if(is_array($value)){
            $this->visible = array_diff_key($this->visible,$value);

            return $this->visible;
        }

        if(array_key_exists($value,$this->visible)) unset($this->visible[$value]);
        return $this->visible;
    }


    /**
     * A default that just sets the standard attributes for plain models
     * @return null
     */
    public function setVisibility(){
       $this->setVisible($this->visible);
       return $this;
    }


    /**
     * Makes sure that all attributes of this model are visible
     * Then sets the skipVisibility variable so they are not overridden
     * @return null
     */
    public function skipVisibility(){
       $this->setVisible(array_keys($this->attributes));
       $this->skipVisibility = true;
       return $this;
    }

    /**
     * Intercepts toArray methods which are run in collections instead of 
     * the toJson unfortunately
     * @return Array
     */
    public function toArray(){
        if($this->skipVisibility){
            return parent::toArray();        
        }

        $this->setVisibility();
        return parent::toArray();        
    }

    /**
     * Intercepts the toJson methods which are run when returning models to the API
     * @param  integer $options Value passed to parent toJson
     * @return Json json string
     */
    public function toJson($options =0 ){
        if($this->skipVisibility){
            return parent::toJson($options);
        }


        $this->setVisibility();
        return parent::toJson($options);
    }


    /**
     * Calculate a unique key for model comparisions and caching
     */
    public function getModelKey()
    {
        return sprintf("%s/%s", 
            get_class($this),
            $this->id
        );
    }

    /**
     * Takes either the slug or the ID and finds the record
     * @param  Integer||String $id 
     * @return  Model
     */
    public static function findBySlugOrId($id){
        if(is_numeric($id)){
            return static::find($id);
        }

        return static::where('slug',$id)->first();
    }

    public function files(){
        return $this->morphMany('App\File','fileable');
    }

}
