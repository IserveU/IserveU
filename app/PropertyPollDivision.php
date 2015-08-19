<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;

class PropertyPollDivision extends ApiModel {

	use Eloquence, Mappable;


	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'property_poll_divisions';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['name', 'voting_station'];

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
	protected $adminVisible = ['id', 'name', 'voting_station'];

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
        'name'				=>  'string',
        'voting_station'	=>  'string'
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
	protected $onCreateRequired = ['name', 'voting_station'];

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
		'name' 				=>	['tag'=>'input','type'=>'string','label'=>'Name of Poll Division','placeholder'=>'Weledeh'],
		'voting_station'	=>	['tag'=>'input','type'=>'string','label'=>'Voting Station','placeholder'=>'St. Patrick High School'],
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

	public function properties(){
		return $this->hasMany('App\Property');
	}

	public function users(){
		return $this->hasManyThrough('App\User','App\Property');
	}

}
