<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use App\Events\MotionUpdated;


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
	protected $fillable = ['title','text','summary'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = ['active'];
	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = ['title','text','summary','department','id','votes','motionRank'];
	
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
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['motionRank'];

    /**
     * The rules for all the variables
     * @var array
     */
	protected $rules = [
		'title' 			=>	'min:8|unique:motions,title',
        'active'			=>	'boolean',
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
	protected $onCreateRequired = ['title','text','user_id'];

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
	 * The fields that are locked. When they are changed they cause events like resetting people's accounts
	 * @var array
	 */
	public $locked = ['title','text'];


	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();

		static::creating(function($model){
			return $model->validate();	
		});

		static::updating(function($model){
			event(new MotionUpdated($model));
			return $model->validate();			
		});

		static::deleting(function($model) { // before delete() method call this
             $model->votes()->delete();
        });
	}


	/************************************* Custom Methods *******************************************/
	
	
	/************************************* Getters & Setters ****************************************/

	/**
	 * @return integer the sum of all the votes on this motion, negative means it's not passing, positive means it's passion
	 */
	
	public function getMotionRankAttribute()
	{
	  // if relation is not loaded already, let's do it first
	  if ( ! array_key_exists('motionRank', $this->relations)) 
	    $this->load('motionRank');
	 
	  $related = $this->getRelation('motionRank');
	 
	  // then return the count directly
	  return ($related) ? (int) $related->rank : 0;
	}

	/**
	 * @param boolean checks that the user is an admin, returns false if not. Automatically sets the closing time to be one week out from now.
	 */
	public function setActiveAttribute($value){
		if(!Auth::user()->can('administrate-motions')){
			return false;
		}

		$this->attributes['active'] = $value;
		$oneWeek = new \DateTime();
		$oneWeek->add(new \DateInterval('P7D'));
		$this->closing = $oneWeek->format("Y-m-d 19:i:00"); //want to make sure that we don't have a system that forces people to be awake at 4:30 am */
		return true;
	}



	/************************************* Casts & Accesors *****************************************/


	/**
	 * @return relation the sum of all the votes on this motion, negative means it's not passing, positive means it's passion
	 */

	public function motionRank()
	{
	  return $this->hasOne('App\Vote')
	    ->selectRaw('motion_id, sum(position) as rank')
	    ->groupBy('motion_id');
	}

	/************************************* Scopes ***************************************************/

	public function scopeActive($query){
		return $query->where('active',1);
	}

	public function scopeExpired($query){
		return $query->where('closing', '<=', new DateTime('NOW'));
	}

	public function scopeCurrent($query){
		return $query->where('closing', '>=', new DateTime('NOW'));
	}

	public function scopePassing($query){
		return $query->votes->where('commentRank','>',0);
	}

	public function scopeFailing($query){
		return $query->votes->where('commentRank','<=',0);
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
		return $this->hasMany('App\Vote')->select(['id','motion_id','position']); //Trying to hide the userid so you can't find out an ID and then figure out their voting record
	}

}
