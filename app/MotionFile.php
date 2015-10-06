<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

use Request;
use Auth;


class MotionFile extends ApiModel
{
    
	use Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'motion_files';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['file_id','motion_id'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = [];
	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = ['id','file_id','motion_id','title','image','file_category_name','filename'];
	
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
	 * The mapped attributes for 1:1 relations
	 * @var array
	 */
   	protected $maps = [
     	'title' 				=> 	'file.title',
     	'image'					=>	'file.image',
     	'filename'				=>	'file.filename',
     	'file_category_name'	=>	'file.filecategory.name'
    ];

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['title','image','filename','file','file_category_name'];

    /**
     * The rules for all the variables
     * @var array
     */
	protected $rules = [
        'motion_id'			=>	'integer',
        'file_id'			=>	'integer|unique:motion_files,file_id',
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
	protected $onCreateRequired = ['file_id','motion_id'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['file_id'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'motion_id' 			=>	['tag'=>'input','type'=>'text','label'=>'Title','placeholder'=>'The unique title of your motion'],
		'file_id'	 			=>	['tag'=>'md-switch','type'=>'X','label'=>'Attribute Name','placeholder'=>''],
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

	public function motion(){
		return $this->belongsTo('App\Motion');
	}

	public function file(){
		return $this->belongsTo('App\File');
	}

}
