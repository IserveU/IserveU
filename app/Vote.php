<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use App\Events\VoteUpdated;
use App\Events\VoteCreated;

use Auth;


class Vote extends ApiModel {

	use SoftDeletes, Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var String
	 */
	protected $table = 'votes';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var Array
	 */
	protected $fillable = ['motion_id','position'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var Array
	 */
	protected $adminFillable = [];

	/**
	 * The default attributes included in the JSON/Array
	 * @var Array
	 */
	protected $visible = ['motion_id','position','id','count','motion','deferred_to_id']; //Count is used in motion controller, motion is used to get user/{id}/vote and have the motion attached

	/**
	 * The attributes visible to an administrator of this model
	 * @var Array
	 */
	protected $adminVisible = ['user_id'];

	/**
	 * The attributes visible to the user that created this model
	 * @var Array
	 */
	protected $creatorVisible = ['motion_id','user_id','position','id'];

	/**
	 * The attributes visible if the entry is marked as public
	 * @var array
	 */
	protected $publicVisible =  ['first_name','last_name','public','id'];

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var Array
	 */	
    protected $appends = [];

    /**
     * The rules for all the variables
     * @var Array
     */
	protected $rules = [
		'motion_id' 	=>	'integer|exists:motions,id|unique_with:votes,user_id',
		'position'		=>	'integer',
		'user_id'		=>	'integer|exists:users,id',
		'id'			=>	'integer'
	];

	/**
	 * The attributes required on an update
	 * @var Array
	 */
	protected $onUpdateRequired = ['id'];

	/**
	 * The attributes required when creating the model
	 * @var Array
	 */
	protected $onCreateRequired = ['motion_id','position','user_id'];
	
	/**
	 * The attributes that are unique so that the exclusion can be put on them on update validation
	 * @var array
	 */
	protected $unique = ['motion_id'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'position' 		=>	['tag'=>'radio','type'=>'integer','label'=>'Position','placeholder'=>''],
		// 	'attribute_name' 		=>	['tag'=>'input','type'=>'email/password','label'=>'Attribute Name','placeholder'=>'Email Address'],
	];

	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	private $locked = [];

	/**************************************** Standard Methods *****************************************/
	public static function boot(){
		parent::boot();
		/* validation required on new */		
		static::creating(function($model){
			return $model->validate();	
		});

		static::updated(function($model){
			event(new VoteUpdated($model));
			return true;
		});

		static::updating(function($model){
			return $model->validate();			
		});

		static::updated(function($model){
			event(new VoteUpdated($model));
			return true;
		});		

	}


	/************************************* Custom Methods *******************************************/
	
	
	/************************************* Getters & Setters ****************************************/
	
	/**
	 * @return Overrides the API Model, will see if it can be intergrated into it
	 */
	public function getVisibleAttribute(){ //Should be manually run because ... fill this in if you can think of a reason

		if(Auth::user()->id==$this->user_id){
			$this->setVisible = array_unique(array_merge($this->creatorVisible, $this->visible));
		}

		if(($this->user) && $this->user->public){ //I'm really confused how a vote can not have a user somehow
			$this->setVisible = array_unique(array_merge($this->publicVisible, $this->visible));
		}

		return parent::getVisibleAttribute();
	}

	public function setPositionAttribute($value){
		if(Auth::user()->id == $this->user_id){
			$this->attributes['deferred_to_id']		= NULL;
		}
		$this->attributes['position'] 			= $value;


	}


	/************************************* Casts & Accesors *****************************************/


	
	/************************************* Scopes ***************************************************/

	public function scopeActive($query){
		return $query->whereNotNull('deferred_to_id');
	}

	public function scopePassive($query){
		return $query->whereNotNull('deferred_to_id');
	}

	public function scopeAgree($query){
		return $query->where('position',1);
	}

	public function scopeDisagree($query){
		return $query->where('position',-1);
	}

	public function scopeAbstain($query){
		return $query->where('position',0);
	}


	/************************************* Relationships ********************************************/

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function motion(){
		return $this->belongsTo('App\Motion');
	}

	public function comment(){
		return $this->hasOne('App\Comment');
	}

	public function deferred(){
		return $this->belongsTo('App\User','deferred_to_id');
	}
}