<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use Carbon\Carbon;
use Setting;

use App\Events\Motion\MotionUpdated;
use App\Events\Motion\MotionCreated;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Repositories\StatusTrait;


class Motion extends ApiModel {

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
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = [];
	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = ['title','text','summary','department_id','id','votes',
						  'MotionOpenForVoting','closing','user_vote',
						  'status','updated_at','slug'];
	
	/**
	 * The attributes hidden in the JSON/Array
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = ['status','user_id'];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = ['status','user_id'];


	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['MotionOpenForVoting','user_vote'];

  

	/**
	 * The variables that are required when you do an update
	 * @var array
	 */
	protected $onUpdateRequired = ['id'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['title'];

	/**
	 * The fields that are dates/times
	 * @var array
	 */
	protected $dates = ['created_at','updated_at','closing'];

	/**
	 * The fields that are locked. When they are changed they cause events like resetting people's accounts
	 * @var array
	 */
	protected $locked = ['title','text'];

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

	public function department(){
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

	public function thisUserVote(){
		if(Auth::check()){
			return $this->hasOne('App\Vote')->where('user_id',Auth::user()->id);
		}
		return $this->hasOne('App\Vote');
	}

}
