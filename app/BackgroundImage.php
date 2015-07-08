<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BackgroundImage extends ApiModel
{

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'background_images';

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
	protected $visible = ['file','display_date'];
	
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
    protected $appends = [];

    /**
     * The rules for all the variables
     * @var array
     */
	protected $rules = [
        'active'			=>	'boolean',
        'file'				=>	'string',
        'display_date' 		=>	'date',
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
	protected $unique = ['display_date'];

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
	public $locked = ['file'];


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
	
	public function random(){
		$random_image = BackgroundImage::orderByRaw("RAND()")->active()->first();
		return $random_image;
	}
	
	/************************************* Getters & Setters ****************************************/


	/**
	 * @return Overrides the API Model, will see if it can be intergrated into it
	 */
	public function getVisibleAttribute(){
		if(!Auth::check()){
			return $this->visible;
		}
		return parent::getVisibleAttribute();
	}


	/**
	 * @param boolean checks that the user is an admin, returns false if not. Automatically sets the closing time to be one week out from now.
	 */
	public function setActiveAttribute($value){
		if(!Auth::user()->can('edit-motions')){
			return false;
		}

		$this->attributes['active'] = $value;
		$oneWeek = new \DateTime();
		$oneWeek->add(new \DateInterval('P7D'));
		$this->closing = $oneWeek->format("Y-m-d 19:i:00"); //want to make sure that we don't have a system that forces people to be awake at 4:30 am */
		return true;
	}



	/************************************* Casts & Accesors *****************************************/

	public function scopeActive($query){
		return $query->where('active',1);
	}

	/************************************* Scopes ***************************************************/


	/************************************* Relationships ********************************************/

	public function user(){
		return $this->belongsTo('App\User');
	}

}
