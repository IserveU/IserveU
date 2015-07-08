<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
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
	protected $visible = ['motion_id','position','id','count']; //Count is used in motion controller

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
    protected $appends = ['user'];

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

		static::updating(function($model){
			return $model->validate();			
		});		
	}


	/************************************* Custom Methods *******************************************/
	
	
	/************************************* Getters & Setters ****************************************/
	
	/**
	 * @return Overrides the API Model, will see if it can be intergrated into it
	 */
	public function getVisibleAttribute(){ //Should be manually run because ... fill this in if you can think of a reason
		if(Auth::user()->id==$this->user_id){
			$this->setVisible($this->creatorVisible);
			return true;
		} 

		if($this->user){
			if($this->user->public){
				$this->setVisible($this->publicVisible);
				return false;
			}
		}

		parent::getVisibleAttribute();
	} 

	/************************************* Casts & Accesors *****************************************/
	
	/************************************* Scopes ***************************************************/

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
}