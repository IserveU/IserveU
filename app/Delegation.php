<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends ApiModel
{
    
	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'delegations';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['delegate_to_id','delegate_from_id','department_id'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = [];

	/**
	 * The default attributes included in any JSON/Array
	 * @var array
	 */
	protected $visible = [];

	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = [];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = ['delegate_to_id','department_id','delegate_from_id'];

	/**
	 * The attributes visible if the entry is marked as public
	 * @var array
	 */
	protected $publicVisible =  ['delegate_to_id','delegate_from_id','department_id'];

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
	    'delegate_to_id'		=>	'integer|exists:users,id',
	    'delegate_from_id'		=>	'integer|exists:users,id|unique_with:delegations,department_id',
	    'department_id'			=>	'integer|exists:departments,id|unique_with:delegations,delegate_from_id'
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
	protected $onCreateRequired = ['delegate_to_id','delegate_from_id','department_id'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['delegate_from_id','department_id'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'delegate_to_id' 					=>	['tag'=>'input','type'=>'email','label'=>'x','placeholder'=>'x'],
		'delegate_from_id' 					=>	['tag'=>'input','type'=>'email','label'=>'x','placeholder'=>'x'],
		'department_id' 					=>	['tag'=>'input','type'=>'email','label'=>'x','placeholder'=>'x'],
	];


	/**
	 * The fields that are dates/times
	 * @var array
	 */
	protected $dates = ['verified_until','created_at','updated_at'];
	
	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	private $locked = ['first_name','middle_name','last_name','date_of_birth'];


	/**************************************** Standard Methods **************************************** */

	public static function boot(){
		parent::boot();

		/* validation required on new */		
		static::creating(function($model){
			if(!$model->validate()) return false;

			return true;
		});

		static::updating(function($model){
			if(!$model->validate()) return false;

			return true;
		});
	}


	/**************************************** Custom Methods **************************************** */
    


	/****************************************** Getters & Setters ************************************/

	public function totals($query){
		return $query->groupBy('delegate_to_total'); 
	}


	/************************************* Casts & Accesors *****************************************/



	/************************************* Scopes *****************************************/
   	


	/**********************************  Relationships *****************************************/


	public function delegateTo(){
		return $this->belongsTo('App\User','delegate_to_id');
	}

	public function delegateFrom(){
		return $this->belongsTo('App\User','delegate_from_id');
	}

	public function department(){
		return $this->belongsTo('App\Department');
	}


}
