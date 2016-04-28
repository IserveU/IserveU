<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use Carbon\Carbon;
use Setting;

use App\Events\Motion\MotionUpdated;
use App\Events\Motion\MotionCreated;

use App\Section\Sectionable;

class Motion extends ApiModel {

	use SoftDeletes, Eloquence, Mappable, Sectionable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'motions';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['title','text','summary','department_id','closing','status','sections'];

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
						  'MotionOpenForVoting','closing','motion_rank','user_vote',
						  'status', 'updated_at', 'sections'];
	
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
	 * The mapped attributes for 1:1 relations
	 * @var array
	 */
   	protected $maps = [
       	'motion_rank'		=> 	'lastestRank.rank',
       	'user_vote'			=>	'userVote'
    ];

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['MotionOpenForVoting','motion_rank','user_vote'];

  

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
		'status'	=> 0 //default draft
	];

	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();

		static::creating(function($model){
		
			return true;	
		});

		static::created(function($model){
			event(new MotionCreated($model));
			return true;	
		});


		static::updating(function($model){
			event(new MotionUpdated($model));
			return true;			
		});

		static::deleting(function($model) { // before delete() method call this
            $model->votes()->delete();
            $model->files()->delete();
        });
	}

	
	
	/************************************* Getters & Setters ****************************************/

	public function setClosingAttribute($value){
		if(!$this->motionRanks->isEmpty()){
			abort(403, "People have already began voting on this motion, you can not change its closing date");
		}

		$this->attributes['closing'] = $value;
		return true;
	}

	public function getMotionOpenForVotingAttribute(){

		/**
		*	This is a localized economies forever open thing.
		*/
		if($this->closing === null){
			return true;
		}

		//Created without a status but not saved
		if(!array_key_exists('status',$this->attributes)){
			return false;
		}

		if($this->attributes['status'] != 2) {
			$this->errors = "This motion is not published and cannot be voted on";
			return false;
		}

		if($this->closing->lt(Carbon::now())){
			$this->errors = "This motion is closed for voting";
			return false;
		}		

		return true;
	}

	/**
	 * @param boolean checks that the user is an admin, returns false if not. Automatically sets the closing time to be one week out from now.
	 */
	public function setStatusAttribute($value){
		//Converts value to the integer if not set
		if(!is_numeric($value)){
			switch ($value){
				case 'draft':
					$value = 0;
					break;
				case 'review':
					$value = 1;
					break;
				case 'published':
					$value = 2;
					break;
				case 'closed':
					$value = 3;
					break;
			}
		}

		$this->attributes['status'] = $value;


		return true;
	}

	/************************************* Casts & Accesors *****************************************/



	/************************************* Scopes ***************************************************/

	public function scopePublished($query){
		return $query->where('status',2);
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
		return $this->hasMany('App\Vote'); //->select(['id','motion_id','position','deferred']); //Trying to hide the userid so you can't find out an ID and then figure out their voting record
	}

	public function motionFiles(){
		return $this->hasMany('App\MotionFile');
	}

	public function files(){
		return $this->hasManyThrough('App\File','App\MotionFile','motion_id','id');
	}

	public function motionRanks(){
		return $this->hasMany('App\MotionRank');
	}

	
	public function lastestRank(){
		return $this->hasOne('App\MotionRank')->latest();
	}

	public function userVote(){
		if(Auth::check()){
			return $this->hasOne('App\Vote')->where('user_id',Auth::user()->id);
		}
		else{ 
			return $this->hasMany('App\Vote');
		}
	}

}
