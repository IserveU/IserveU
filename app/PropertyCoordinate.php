<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;


class PropertyCoordinate extends ApiModel {

	use Eloquence, Mappable;


	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'property_coordinates';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['latitude', 'longitude', 'block_id', 'last_coordinate_id'];

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
	protected $adminVisible = ['id', 'latitude', 'longitude', 'block_id', 'last_coordinate_id'];

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
        'id'					=>	'integer',
        'latitude'				=>  'numeric',
        'longitude'				=>  'numeric',
        'block_id'				=>	'integer',
        'last_coordinate_id'	=>	'integer'
	];

	/**
	 * The variables that are required when you do an update
	 * @var array
	 */
	protected $onUpdateRequired = ['id', 'latitude', 'longitude', 'block_id'];

	/**
	 * The variables requied when you do the initial create
	 * @var array
	 */
	protected $onCreateRequired = ['latitude', 'longitude', 'block_id'];

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
		'latitude' 					=>	['tag'=>'input','type'=>'double','label'=>'Latitude','placeholder'=>'Latitude'],
		'longitude'					=>	['tag'=>'input','type'=>'double','label'=>'Longitude','placeholder'=>'Longitude'],
		'block_id' 					=>	['tag'=>'input','type'=>'integer','label'=>'Block ID','placeholder'=>'Block ID'],
		'last_coordinate_id'		=>	['tag'=>'input','type'=>'integer','label'=>'Last Coordinate ID','placeholder'=>'Last Coordinate ID'],
	];

	/**
	 * The fields that are locked. When they are changed they cause events like resetting people's accounts
	 * @var array
	 */
	public $locked = [];

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

	public function propertyBlock(){
		return $this->belongsTo('App\PropertyBlock');
	}

}
