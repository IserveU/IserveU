<?php

namespace App\Motion;

use Illuminate\Database\Eloquent\Model;

use App\Motion\Bio;
use App\Motion\Budget;
use App\Motion\Text;

class MotionSection extends \App\ApiModel
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'motion_section';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'motion_id'
    ];

    /**
     * The attributes that should be casted to JSON
     *
     * @var array
     */
    // protected $casts = [
    //     'content' => 'array'
    // ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * Anytime you get articles you generally might as well do a join and get the rest of the model in the same query
     *
     * @var array
     */
    protected $with = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    public static function boot(){
        parent::boot();
  
        static::created(function($model){
            return true;
        });

        static::updated(function($model){
            return true;
        });
    }

    /**************************************** Getters and Setters **********************************/

    public function setMotionIdAttribute($value){

        $this->attributes['motion_id'] = $value;
        return true;
    }

    public function setContentAttribute($value){

        $this->attributes['content'] = json_encode($value);
        return true;
    }

    public function getContentAttribute($value){
        return json_decode($this->attributes['content']);
    }

    /**************************************** Defined Relationships ****************************************/

    /**
     * Get the parent motion.
     */
    public function motion()
    {
        return $this->belongsTo('App\Motion', 'motion_id');
    }

}
