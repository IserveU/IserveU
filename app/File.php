<?php

namespace App;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\FileCategory;

use Illuminate\Support\Facades\Validator;

class File extends ApiModel
{
     
	use Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'files';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['filename','title','file_category_id','file'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = [];
	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = []; //Hide because government IDs can be uploaded here
	
	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = ['id','filename','title','type'];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = ['id','filename','title','type'];

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
		'title' 				=>	'sometimes',
        'filename'				=>	'min:30|unique:files,filename',
        'file'					=>	'mimes:png,pdf,jpg,jpeg,gif',
        'image'					=>	'boolean',
        'file_category_id'		=>	'integer',
        'id'					=>	'integer',
        'file'					=>	'file'
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
	protected $onCreateRequired = ['filename','file_category_id'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['filename'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'title' 		=>	['tag'=>'input','type'=>'text','label'=>'Title','placeholder'=>'The title/caption/name of your filename'],
		'file'	 		=>	['tag'=>'file','type'=>'X','label'=>'Attribute Name','placeholder'=>''],
		'filename'	 	=>	['tag'=>'md-switch','type'=>'X','label'=>'Attribute Name','placeholder'=>''],
		'image'	 		=>	['tag'=>'md-switch','type'=>'hidden','label'=>'The file type of this','placeholder'=>''],
		'category_id' 		=>	['tag'=>'input','type'=>'text','label'=>'Title','placeholder'=>'The title/caption/name of your file'],
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
			if(!$model->validate()) return false;
			return true;
		});

		static::updating(function($model){
			if(!$model->validate()) return false;
			return true;
		});

		static::deleted(function($model){
			\File::delete(getcwd()."/uploads/".$model->fileCategory->name."/".$model->filename);
			return true;
		});

	}


	/************************************* Custom Methods *******************************************/
	
	public function uploadFile($file_category_name,$input_name="file",Request $request){



		$file_category = FileCategory::where('name',$file_category_name)->first();
		if(!$file_category){
			$file_category = FileCategory::create(['name'=>$file_category_name]);
		}

		$this->file_category_id = $file_category->id;
		$this->title 			= $request->input('title');

		$file = $request->file($input_name);
		if(!$file){
			return false;
		}


		try{
			$mimeType = $file->getMimeType();
		} catch (\Exception $e){
			\Log::error($e->getMessage()); //Sometimes if the file is too big, but it will catch it later on when you try to move the file
		}


		if(isset($mimeType) && substr($mimeType, 0, 5) == 'image') {		

			try {
	      		$img = Image::make($file)->resize(1920, null, function($constraint){
	      			$constraint->aspectRatio();
	      			$constraint->upsize();
	      		});
			} catch (Exception $e) {
			    abort(400,'There was an error uploading and resizing the image');
			} catch (NotReadableException $e){
				abort(403,"Unable to read image from file");
			}

			$this->image = true;

		    $filename = md5($img->response()).".png";
		    $img->save(getcwd()."/uploads/".$this->fileCategory->name."/$filename");   
		} else {
		    $filename = md5($file).".".$file->getClientOriginalExtension();
			$file->move(getcwd()."/uploads/".$this->fileCategory->name, $filename);
			$this->image = false;
		}

		$this->filename 	=	$filename;

	}
	
	/************************************* Getters & Setters ****************************************/




	/************************************* Casts & Accesors *****************************************/



	/************************************* Scopes ***************************************************/



	/************************************* Relationships ********************************************/

	public function motion(){
		return $this->belongsToManyThrough('App\Motion','App\Figure');
	}

	public function userIdentification(){
		return $this->belongsTo('App\User','government_identification_id');
	}

	public function userAvatar(){
		return $this->belongsTo('App\User','avatar_id');
	}

	public function fileCategory(){
		return $this->belongsTo('App\FileCategory');
	}

}
