<?php 
namespace App\Repositories;

use Carbon\Carbon;

/**
*   A reusable status and published trait to manage visibility of montions and users
**/

trait StatusTrait{ 

    /**
     * Gets the statuses considered visible by this model to the general public
     * @return [type] [description]
     */
    public static function visibleStatuses(){
        return array_keys(array_filter(static::$statuses, function($value, $key){
            return ($value == 'visible');
        }, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Gets the statuses considered visible by this model to the general public
     * @return [type] [description]
     */
    public static function hiddenStatuses(){
        return array_keys(array_filter(static::$statuses, function($value, $key){
            return ($value == 'hidden');
        }, ARRAY_FILTER_USE_BOTH));
    }



    /**
    * Can be run by the model to check the status as valid
    *
    * @return boolean true 
    */

    public function validStatus(){
        $validator = \Validator::make($this->attributes,[
            'status' =>      'valid_status'
        ]);
        //This hasn't been catching in handler in the tests at least. WTF?
        if($validator->fails()) {
            throw new \Illuminate\Foundation\Validation\ValidationException($validator);
        }
        return true;
    }   


    public function scopeStatus($query,$status){
        if(is_array($status)){
            return $query->whereIn('status',$status);
        }
        return $query->where('status',$status);
    }

    /**
     * Get the public visibility of this model
     * @return boolean If the model is considered to be publically visible
     */
    public function getPubliclyVisibleAttribute(){
        if(static::$statuses[$this->status]=='visible'){
            return true;
        }
        return false;
    }

    public function scopeVisible($query){
        $query->whereIn('status',static::visibleStatuses());
    } 

    public function scopeHidden($query)
    {
        $query->whereIn('status',static::hiddenStatuses());
    }

    public function scopePublishedBefore($query, $time){
        return $query->whereDate('published_at', '<', $time);
    }

     public function scopePublishedAfter($query, $time){
        return $query->whereDate('published_at', '>', $time);
    }



    /*
    * Handles the trailing data error
    */
    public function setPublishedAtAttribute($datetime)
    {
        try {
            $this->attributes['published_at'] =  Carbon::parse($datetime); 
        }
          catch (\Exception $err) {
            $this->attributes['published_at'] =  Carbon::createFromFormat('D M d Y H:i:s e+', $datetime); // Thu Nov 15 2012 00:00:00 GMT-0700 (Mountain Standard Time)
        }
    }

    
    /**
    * This scope puts null fields first (drafts) and then the latest
    ***/

    public function scopeLatest($query){
        $query->orderBy(\DB::raw('-published_at'), 'asc');
    }


}