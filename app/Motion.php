<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use Carbon\Carbon;
use App\Events\MotionUpdated;
use App\Events\MotionCreated;
use Setting;


class Motion extends ApiModel {

	use SoftDeletes, Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'motions';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['title','text','summary','department_id', 'closing'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = ['active'];
	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = ['title','text','summary','department_id','id','votes','MotionOpenForVoting','closing','motion_rank','user_vote'];
	
	/**
	 * The attributes hidden in the JSON/Array
	 * @var array
	 */
	protected $hidden = [];
	

	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = ['active','user_id'];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = ['active','user_id'];

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
     * The rules for all the variables
     * @var array
     */
	protected $rules = [
		'title' 			=>	'min:8|unique:motions,title',
        'active'			=>	'boolean',
        'department_id'		=>	'exists:departments,id',
        'closing' 			=>	'date',
        'text'				=>	'min:10',
        'user_id'			=>	'integer|exists:users,id',
        'id'				=>	'integer'
	];

	/**
	 * The variables that are required when you do an update
	 * @var array
	 */
	protected $onUpdateRequired = ['id'];

	/**
	 * The variables requied when you do the initial create
	 * @var array
	 */
	protected $onCreateRequired = ['title','text','user_id','department_id'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['title'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'title' 				=>	['tag'=>'input','type'=>'text','label'=>'Title','placeholder'=>'The unique title of your motion'],
		'active'	 			=>	['tag'=>'md-switch','type'=>'X','label'=>'Attribute Name','placeholder'=>''],
		'closing'	 			=>	['tag'=>'md-switch','type'=>'X','label'=>'Attribute Name','placeholder'=>''],
		'text'	 				=>	['tag'=>'md-switch','type'=>'X','label'=>'Attribute Name','placeholder'=>''],
	];


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


	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();

		static::creating(function($model){
			if(!$model->validate()) return false;
			return true;	
		});

		static::created(function($model){
			event(new MotionCreated($model));
			return true;	
		});


		static::updating(function($model){
			if(!$model->validate()) return false;
			event(new MotionUpdated($model));
			return true;			
		});

		static::deleting(function($model) { // before delete() method call this
            $model->votes()->delete();
            $model->files()->delete();
        });
	}


	/************************************* Custom Methods *******************************************/
	
	
	/************************************* Getters & Setters ****************************************/


	/**
	 * @param boolean checks that the user is an admin, returns false if not. Automatically sets the closing time to be one week out from now.
	 */
	public function setActiveAttribute($value){

		if(Auth::check() && !Auth::user()->can('administrate-motions')){
			abort(401,"Unable to set  user does not have permission to set motions as active");
		}

		if($value && !$this->motionRanks->isEmpty()){
			abort(403,"This motion has already been voted on, it cannot be reactivated after closing");
		}

		$this->attributes['active'] = $value;

		if(!$this->closing && $value == 1){
			$closing = new Carbon;
			$closing->addHours(Setting::get('motion.default.closing_delay',72));
			$this->closing = $closing;
		}
		return true;
	}

	public function setClosingAttribute($value){
		if(!$this->motionRanks->isEmpty()){
			abort(403, "People have already began voting on this motion, you can not change its closing date");
		}

		$this->attributes['closing'] = $value;
		return true;
	}


    public function getClosingAttribute($attr) {
    	if(!$attr){
    		return $attr;
    	}

        $carbon = Carbon::parse($attr);

        return array(
            'diff'          =>      $carbon->diffForHumans(),
            'alpha_date'    =>      $carbon->format('j F Y'),
            'carbon'     	=>      $carbon
        );
    }

	public function getActivelyAgreeAttribute($value){
		
	}

	public function getActivelyDisagreeAttribute($value){

	}

	public function getActivelyAbstainAttribute($value){

	}

	public function getPassivelyDisagreeAttribute($value){

	}

	public function getPassivelyAgreeAttribute($value){

	}

	public function getPassivelyAbstainAttribute($value){

	}


	public function getMotionOpenForVotingAttribute(){
		if(!$this->active){
			$this->errors = "This motion is not active and cannot be voted on";
			return false;
		}

		if($this->closing['carbon']->lt(Carbon::now())){
			$this->errors = "This motion is closed for voting";
			return false;
		}		

		return true;
	}



	/************************************* Casts & Accesors *****************************************/



	/************************************* Scopes ***************************************************/

	public function scopeActive($query){
		return $query->where('active',1);
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


	// public function scopePassing($query){
	// 	return $query->whereHas('votes',function($query){
	// 		$query->havingRaw('SUM(position) > 0');
	// 	});
	// 	//return $query->votes->where('commentRank','>',0);
	// }

	// public function scopeFailing($query){
	// 	return $query->whereHas('votes',function($query){
	// 		$query->havingRaw('SUM(position) <= 0');
	// 	});
	// 	//return $query->votes->where('commentRank','<=',0);
	// }

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
		return $this->hasOne('App\Vote')->where('user_id',Auth::user()->id);
	}




}
