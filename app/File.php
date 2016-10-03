<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Auth;
use Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\File\CreateFileRequest;
use App\Http\Requests\File\UpdateFileRequest;
use Carbon\Carbon;

use App\Article;
use App\Filters\QueryFilter;
use App\Repositories\ArrayHelper;
use App\Repositories\FileTypeCategoryHelper;

use Intervention\Image\ImageManagerStatic as Images;
use App\Repositories\Contracts\CachedModel;

use Cviebrock\EloquentSluggable\Sluggable;
use Flow\Config as FlowConfig;
use Flow\Request as FlowRequest;
use Flow\Basic as FlowBasic;
use Illuminate\Support\Facades\Input;


class File extends NewApiModel implements CachedModel {

    use Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'files';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','description','user_id','folder'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
    ];

   /**
     * Require default attribute values
     *
     * @var array
     */

    protected $attributes = [
 
    ];


    protected $flowFilename = '';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'filename',
                'save_to' =>'slug'        ]
        ];
    }


    /**************************************** Standard Methods ****************************************/

    protected $file;

    public static function boot(){
        parent::boot();

        static::creating(function($model){

            return true; 
        });

        static::saving(function($model){
            if(!$model->user_id){
                $model->user_id = \Auth::user()->id;
            }
            
            $destinationPath = storage_path().'/app/'.$model->folder;
            
            if(!file_exists($destinationPath)) {
                \File::makeDirectory($destinationPath);
            }
            if($model->folder){
                \File::move(storage_path().'/app/'.$model->filename,$destinationPath.'/'.$model->filename);
            }

            
            if(\File::exists($destinationPath.$model->filename) ){
                $mimeType = \File::mimeType($destinationPath.$model->filename);
                $model->mime = substr($mimeType, strrpos($mimeType, '/') + 1);
                $model->type = File::categorizeTypeOfFile($model->filename, $mimeType);
                \Log::info('storing a '.$model->type_category);
                                          
            }
        return true; 
        });

        static::saved(function($model){

            $model->flushCache($model);
            $model->flushRelatedCache($model);

        });
        static::updated(function($model){
        });
        static::deleted(function($model){

            $model->flushCache($model);    
            $model->flushRelatedCache($model);
            
            Storage::delete($model->filename);

        });
    }


    /********************************Cache Clear Interface Method Implementation***************************/
   

    /**
     * Clear cache of this model
     *
     * @return null
     */
    public function flushCache($fromModel = null){
        
    }

    /**
     * Clear cache of models with caching related to this model
     *
     * @return null
     */
    public function flushRelatedCache($fromModel = null){

     

    }


    /**************************************** Getters and Setters ****************************************/


    public function setFilenameAttribute($filename){
        $this->attributes['filename'] = $filename;
    }

    public function setFolderAttribute($folder){
        $folder = snake_case($folder);
        $this->attributes['folder'] = $folder;
    }

    public function getLocationAttribute(){
        if($this->folder){
            $path = 'app/'.$this->folder;
        }
        return storage_path('app/'.$this->folder."/".$this->filename);
    }

    public function getPublicFilenameAttribute(){
        $name = str_slug($this->title);
        if(!$name){
            $name = "download_".$this->type.'.'.\File::extension($this->filename);
        }

        return $name;
    }



    /**
     * Checks to see if the file is immediately or distantly the child of an object
     * @param  ApiModel $parent thing to check relation with
     * @return boolean   If this is related to the parent or not
     */
    public function isAssociatedWith($parent){
        
        if(!$this->fileable){
            return false; //Can't be associated with anything
        }

        //Direct relation (hero image/avatar)       
        if($this->fileable->getModelKey() == $parent->getModelKey()) return true;
 
        return false;
    }


    
    public static function categorizeTypeOfFile(String $filename,String $mimeType){
        
            $type_category = substr($mimeType, 0, strrpos($mimeType, '/'));
        
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $documents = array("doc","docx","xls","xlsx","pdf","ppt","pptx");
            $archives = array("zip","rar");

            if(in_array($extension,$documents)) return $type_category = "document";
            if(in_array($extension,$archives)) return $type_category = "archive";

            else return $type_category;
    }


    public function resize($width = null,$height = null){

        return Images::cache(function($image) use ($width,$height){

            return $image->make($this->location)->resize($width,$height, function ($constraint) {

                $constraint->aspectRatio();
                $constraint->upsize();
            });
        },20,true); 

    }


/***************************SCOPE FUNCTIONS ***********************************************************************/    
  

    /**************************************** Defined Relationships ****************************************/

    /**
     * The things files are attached to, at this stage almost always one image
     * @return Image The image this file is attached to
     */
    public function fileable(){
        return $this->morphTo();   
    }

    /**
     * Every file has either an owner or uploader
     */
    public function user(){
        return $this->belongsTo('App\User');
    }

	public function userIdentification(){
		return $this->belongsTo('App\User','government_identification_id');
	}

	public function userAvatar(){
		return $this->belongsTo('App\User','avatar_id');
	}


}
