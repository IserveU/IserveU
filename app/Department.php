<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use DB;
use Illuminate\Support\Facades\Validator;
use Request;

class Department extends ApiModel {

	use SoftDeletes, Eloquence, Mappable;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'departments';

	/**
	 * The attributes that are mass assignable.
	 * administrator: 				We want to know if someone is becoming an administrator
	 * verified_until/property_id: 	If a property_id changes/we need to reverify the person
	 * hash/pasword:				Seems like these should be setup moremanually
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'enabled'];


	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	protected $visible = ['name','enabled','id'];

	protected $rules = [	
		'name' 				=>	'string|unique:departments',
	    'enabled'			=>	'boolean'
	];

	protected $maps = [
     	'department' 			=> 	['motion_id'] /* User ID is here for the benefit of the getMotionComments */
    ];

	public $fields = [
		'name' 					=>	['tag'=>'input','type'=>'input','label'=>'Department','placeholder'=>'Department'],
	];
	
	private $locked = [];


	public function secureFill(array $input){
		return parent::fill($input);
	}





	/**************************************** Standard Methods **************************************** */

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


	/**************************************** Custom Methods **************************************** */
  
 

	/****************************************** Getters & Setters ************************************/

	public function getVisibleAttribute(){ 
		return $this->visible;
	}

	public function getFillableAttribute(){
		return $this->fillable;
	}

	public function getRulesAttribute(){
		return $this->rules;	
	}

	
	/************************************* Casts & Accesors *****************************************/
	public function toJson($options = 0) {
		$this->getVisibleAttribute();
		return parent::toJson();
	}

	public function toArray() {
		$this->getVisibleAttribute();
		return parent::toArray();
	}



	/************************************* Scopes *****************************************/


	/**********************************  Relationships *****************************************/


	public function motions(){
		return $this->hasMany('App\Motion');
	}


}
