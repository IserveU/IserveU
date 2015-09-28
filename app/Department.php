<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use DB;
use Illuminate\Support\Facades\Validator;
use Request;

use App\Events\DepartmentCreated;

class Department extends ApiModel {

	use SoftDeletes, Eloquence, Mappable;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'departments';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['name', 'active'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = ['name', 'active'];

	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = ['name','active','id'];

	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = ['active','id'];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = ['active','id'];

    /**
     * The rules for all the variables
     * @var array
     */
	protected $rules = [	
		'name' 				=>	'string|unique:departments,name',
	    'active'			=>	'boolean',
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
	protected $onCreateRequired = ['name','active'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['name'];


	public $fields = [
		'name' 					=>	['tag'=>'input','type'=>'input','label'=>'Department','placeholder'=>'Department'],
		'active'	 			=>	['tag'=>'md-switch','type'=>'X','label'=>'Attribute Name','placeholder'=>'']
	];
	
	protected $locked = [];


	/**************************************** Standard Methods **************************************** */

	public static function boot(){
		parent::boot();

		static::creating(function($model){
			if(!$model->validate()) return false;
			return true;
		});

		static::created(function($model){
			event(new DepartmentCreated($model));
			return true;
		});

		static::updating(function($model){
			if(!$model->validate()) return false;
			return true;
		});
	}


	/**************************************** Custom Methods **************************************** */
  
 

	/****************************************** Getters & Setters ************************************/

	
	/************************************* Casts & Accesors *****************************************/

	/************************************* Scopes *****************************************/


	/**********************************  Relationships *****************************************/


	public function motions(){
		return $this->hasMany('App\Motion');
	}

	public function delegations(){
		return $this->hasMany('App\Delegations');
	}

}
