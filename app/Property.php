<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends ApiModel {


	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'properties';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['unit','roll_number','address','street','block','plan','poll_division','zone','coordinate','description','postal_code'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = [];

	/**
	 * The default attributes included in any JSON/Array
	 * @var array
	 */
	protected $visible = ['unit','roll_number','address','street','block','plan','poll_division','zone','coordinate','description','postal_code'];

	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = [];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = [];

	/**
	 * The attributes visible if the entry is marked as public
	 * @var array
	 */
	protected $publicVisible =  []; //NA

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = [];

    /**
     * The rules for all the variables
     * @var array
     */
	protected $rules = [
		'unit'						=>		'string',
		'roll_number'				=>		'unique:properties',
		'address'					=>		'string',
		'street'					=>		'string',
		'property_block_id'			=>		'integer',
		'property_plan_id'			=>		'integer',
		'property_poll_division_id'	=>		'integer',
		'property_zone_id'			=>		'integer',
		'property_coordinate_id'	=>		'integer',
		'property_description_id'	=>		'integer',
		'postal_code'				=>		'string'	
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
	protected $onCreateRequired = ['roll_number','property_block_id','property_plan_id','property_description_id','property_poll_division_id'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['roll_number'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'unit'						=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'roll_number'				=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'address'					=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'street'					=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'property_block_id'			=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'property_plan_id'			=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'property_poll_division_id'	=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'property_zone_id'			=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'property_coordinate_id'	=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'property_description_id'	=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>''],
		'postal_code'				=>		['tag'=>'','type'=>'','label'=>'','placeholder'=>'']
	];
	
	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	protected $locked = [];


	/**************************************** Standard Methods **************************************** */

	public static function boot(){
		parent::boot();

		static::creating(function($model){
			return $model->validate();	
		});

		static::updating(function($model){
			return $model->validate();			
		});
	}

	
	/**************************************** Custom Methods **************************************** */
 

	/****************************************** Getters & Setters ************************************/


	/************************************* Casts & Accesors *****************************************/


	/************************************* Scopes *****************************************/


	/**********************************  Relationships *****************************************/



	public function propertyBlock(){
		return $this->belongsTo('App\PropertyBlock');
	}

	public function propertyDescription(){
		return $this->belongsTo('App\PropertyDescription');
	}

	public function propertyZone(){
		return $this->belongsTo('App\PropertyZoning');
	}

	public function propertyPollDivision(){
		return $this->belongsTo('App\PropertyPollDivision');
	}

	public function propertyPlan(){
		return $this->belongsTo('App\PropertyPlan');
	}

	public function propertyAssesments(){
		return $this->hasMany('App\PropertyAssessment');
	}

	public function users(){
		return $this->belongsToMany('App\User');
	}

}
