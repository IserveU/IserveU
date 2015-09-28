<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;


class PropertyAssessment extends ApiModel {

	use Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'property_assesments';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['land_value','property_value', 'improvement_value', 'other_value', 'year', 'property_id'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = [];
	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = [];
	
	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = ['id','land_value','improvement_value','other_value','year','property_id'];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = [];

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
        'id'				=>	'integer',
		'land_value' 		=>	'integer',
        'improvement_value'	=>	'integer',
        'other_value' 		=>	'integer',
        'year'				=>	'integer',
        'property_id'		=>	'integer|unique:property_assesments,property_id'
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
	protected $onCreateRequired = ['land_value','year', 'improvement_value','property_id'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = [];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'land_value' 				=>	['tag'=>'input','type'=>'integer','label'=>'Land Value','placeholder'=>''],
		'improvement_value'	 		=>	['tag'=>'input','type'=>'integer','label'=>'improvement Value','placeholder'=>''],
		'other_value'	 			=>	['tag'=>'input','type'=>'integer','label'=>'Other Value','placeholder'=>''],
		'year'	 					=>	['tag'=>'input','type'=>'date','label'=>'Year','placeholder'=>''],
		'property_id'	 			=>	['tag'=>'input','type'=>'integer','label'=>'Property ID','placeholder'=>''],

	];

	/**
	 * The fields that are locked. When they are changed they cause events like resetting people's accounts
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


	/************************************* Custom Methods *******************************************/

	
	
	/************************************* Getters & Setters ****************************************/



	/************************************* Casts & Accesors *****************************************/
	/************************************* Scopes ***************************************************/


	/************************************* Relationships ********************************************/


	public function property(){
		return $this->belongsTo('App\Property');
	}

}
