<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotionSection extends ApiModel
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'motion_section';

    static $types = array('Text','Bio','Budget');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'motion_id', //Standard  methods
        'title','text',  //Extended variables (actually in options field)
        'name', 'position', 'company', 'website', 'avatar_id', 'description',
        'price'
    ];

    /**
     * The attributes that should be casted to JSON
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array'
    ];


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




    /**************************************** Query Scopes ****************************************/

    // public function scopePublished($query)
    // {
    //     $query->where('published_at', '<=', Carbon::now());
    // } 

    // public function scopeNotPublished($query)
    // {
    //     $query->where('published_at', '>', Carbon::now());
    // }

    // public function scopeExpired($query)
    // {
    //     $query->where('expired_at', '<=', Carbon::now());
    // }     

    // public function scopeNotExpired($query)
    // {
    //     $query->where('expired_at', '>', Carbon::now());
    // }     

    // public function scopeActive($query)
    // {
    //     $query->where('expired_at', '>', Carbon::now())
	   //    	  ->where('published_at', '<', Carbon::now());
    // }

    // public function scopeOwnerIs($query,User $owner){
    //     $query->where('user_id',$owner->id);
    // }


    /**************************************** Getters and Setters **********************************/

    // public function setApprovedAttribute($value){
    //     if(\Auth::user()->may('administrate-advertisement')){
    //         $this->attributes['approved'] = $value;
    //         return true;
    //     }
    //     abort(401,'Permission denied');
    // }

    public function setTypeAttribute($value){
        if(in_array($value,self::$types)){
            $this->attributes['type'] = $value;
            return true;
        }
        abort(500,'Invalid Type');
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
