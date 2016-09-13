<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use Carbon\Carbon;
use App\Setting;

use App\Events\Motion\MotionUpdated;
use App\Events\Motion\MotionCreated;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Repositories\StatusTrait;

use App\Repositories\Contracts\CachedModel;
use App\Repositories\Contracts\VisibilityModel;


class Motion extends NewApiModel implements CachedModel, VisibilityModel{

	use SoftDeletes, Sluggable, StatusTrait;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'motions';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['title','text','summary','department_id','closing','status','user_id'];

	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = [''];
	
	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $with = ['departmentRelation'];
	


	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['motionOpenForVoting','userVote','department','userComment'];

  

	/**
	 * The fields that are dates/times
	 * @var array
	 */
	protected $dates = ['created_at','updated_at','closing'];

	/**


	protected $attributes = [
		'title'		=> 	'New Motion',
		'status'	=> 	'draft' //default draft
	];

  
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' 	=> ['title'],
             	'onUpdate'	=> true
            ]
        ];
    }


	protected $attributes = [
		'status'	=>	'private'
	];

    /**
     * The  statuses that a motion can have
     * @var Array
     */
	public static $statuses = [
        'draft'    	=>  'hidden',
        'review'  	=>  'hidden',
        'published' =>  'visible',
        'closed'    =>  'visible'
    ];


	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();

		static::creating(function($model){
			
			if(!$model->user_id){
				$model->user_id = Auth::user()->id;	
			}

			return true;	
		});

		static::created(function($model){
			// Does  Nothing
			event(new MotionCreated($model));
			return true;	
		});


		static::updating(function($model){
			// SendNotificationEmail
			// AlertVoters
			event(new MotionUpdated($model));
			return true;			
		});

		static::deleting(function($model) { // before delete() method call this
           

        });
	}

	//////////////////////// Caching Implementation
 
   /**
     * Remove this items cache and nested elements
     * 
     * @param  Model $fromModel The model calling this (if exists)
     * @return null
     */

    public function flushCache($fromModel = null){
        Cache::tags('motion.'.$vote->motion->id)->flush();
    	Cache::forget('motion'.$vote->motion_id.'_comments');
    	\Cache::flush(); //Just for now
    }

    /**
     * Clears the caches of related models or there relations if needed
     * 
     * @param  Model $fromModel The model calling this (if exists)
     * @return null
     */
    public function flushRelatedCache($fromModel = null){
    	\Cache::flush(); //Just for now
    }


	
	//////////////////////// Visibility Implementation


    public function setVisibility(){

        //If self or show-other-private-user
        if(Auth::check() && (Auth::user()->id==$this->user_id || Auth::user()->can('show-motion'))){
            $this->addVisible(['id','title','summary','slug','text','department','closing','status','created_at','updated_at','user','motionOpenForVoting']);
        }

        if(Auth::check()){
        	$this->addVisible(['userVote','userComment']);
        }

        if($this->publiclyVisible){
			$this->addVisible(['id','title','summary','slug','text','department','closing','status','created_at','updated_at','motionOpenForVoting']);
        }

        return $this;
    }

	/************************************* Getters & Setters ****************************************/

	public function setClosingAttribute($value){
		if(!$this->votes->isEmpty()){
			abort(403, "People have already began voting on this motion, you can not change its closing date");
		}

		$this->attributes['closing'] = $value;
		return true;
	}

	public function getMotionOpenForVotingAttribute(){

		// Motions can stay open forever ATM
		if($this->closing === null) return true;

		//Created without a status but not saved
		if(!array_key_exists('status',$this->attributes)) return false;
		

		//This motion is not published and cannot be voted on
		if($this->attributes['status'] != 'published') return false;
		

		if($this->closing->lt(Carbon::now())){
			$this->attributes['status'] = 'closed';
			$this->save();
			return false;
		}		

		return true;
	}

	public function getUserVoteAttribute(){
		if($this->thisUserVote){
			return $this->thisUserVote->toArray();
		}
		return null;
	}


	public function getUserCommentAttribute(){
		if($this->thisUserVote){
			return $this->thisUserVote->comment;
		}
		return null;
	}
	

	/**
	 * A bridge to the comments on this motion
	 * @return Collection A collection of comments
	 */
	public function getCommentsAttribute(){
		$this->load(['votes.comment' => function ($q) use ( &$comments ) {
		   $comments = $q->get()->unique();
		}]);
		return $comments;
	}


	public function getDepartmentAttribute(){
		return $this->departmentRelation();

		dd($this->departmentRelation); //return $this->department();
	}
	

	/************************************* Casts & Accesors *****************************************/



	/************************************* Scopes ***************************************************/

	//Maybe just depreciate this for the global scope below?
	

	public function scopeStatus($query,$status='published'){
		if(is_array($status)){
			return $query->whereIn('status',$status);
		}
		return $query->where('status',$status);
	}

	public function scopeWriter($query,$user_id){
		return $query->where('user_id',$user_id);
	}

	public function scopeExpired($query){
		return $query->where('closing', '<=', Carbon::now());
	}

	public function scopeCurrent($query){
		return $query->where('closing', '>=', Carbon::now());
	}

	public function scopeDepartment($query,$department_id){
		return $query->where('department_id',$department_id);
	}

	public function scopeUpdatedBefore($query,Carbon $time){	
		return $query->where('updated_at','<=',$time);
	}

	public function scopeUpdatedAfter($query,Carbon $time){	
		return $query->where('updated_at','>=',$time);
	}	

	public function scopeClosingBefore($query,Carbon $time){
		return $query->where('closing','<=',$time);
	}

	public function scopeClosingAfter($query,Carbon $time){
		return $query->where('closing','>=',$time);
	}

	public function scopeOrderByNewest($query){
		return $query->orderBy('created_at', 'asc');
	}

	public function scopeOrderByOldest($query){
		return $query->orderBy('created_at', 'desc');
	}

	public function scopeRankGreaterThan($query,$rank){
		return $query->whereHas('votes',function($query) use ($rank){
			$query->havingRaw('SUM(position) > '.$rank);
		});
	}

	public function scopeRankLessThan($query,$rank){
		return $query->whereHas('votes',function($query) use ($rank){
			$query->havingRaw('SUM(position) < '.$rank);
		});
	}


	/************************************* Relationships ********************************************/

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function event(){
		return $this->belongsTo('App\Event');
	}

	public function departmentRelation(){
		return $this->belongsTo('App\Department');
	}
	
	public function votes(){
		return $this->hasMany('App\Vote');
	}

	public function motionFiles(){
		return $this->hasMany('App\MotionFile');
	}

	public function files(){
		return $this->hasManyThrough('App\File','App\MotionFile','motion_id','id');
	}


	public function comments(){
		return $this->whereHas('votes',function($query) use ($rank){
			$query->havingRaw('SUM(position) < '.$rank);
		});
	}


	public function thisUserVote(){
		if(Auth::check()){
			return $this->hasOne('App\Vote')->where('user_id',Auth::user()->id);
		}
		return $this->hasOne('App\Vote');
	}

}
