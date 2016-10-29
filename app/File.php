<?php

namespace App;

use App\Repositories\Caching\CachedModel;
use App\Repositories\FileUploadHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Images;
use Storage;

class File extends NewApiModel implements CachedModel
{
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
        'title', 'description', 'user_id', 'folder', 'replacement_id', 'filename',
    ];


    /**
     * So that the motion page can see the index.
     *
     * @var array
     */
    protected $with = ['previousVersion'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [

    ];

    /**
     * The attributes in the model's JSON form.
     *
     * @var array
     */
    protected $visible = [
        'id', 'slug', 'title', 'description', 'replacement_id', 'type', 'mime', 'fileable_id', 'fileable_type', 'previousVersion',
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
     * Require default attribute values.
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
                'source'  => 'filename',
                'save_to' => 'slug', ],
        ];
    }

    /**************************************** Standard Methods ****************************************/

    protected $file;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            return true;
        });

        static::saving(function ($model) {
            if (!$model->user_id) {
                $model->user_id = \Auth::user()->id;
            }

            $destinationPath = storage_path('app/');

            if ($model->folder) {
                $destinationPath .= $model->folder.'/';
            }

            if (!file_exists($destinationPath)) {
                \File::makeDirectory($destinationPath);
            }

            if (!$model->filename) {
                return true;
            }

            if (!\File::exists($destinationPath.$model->filename)) {
                \File::move(storage_path().'/app/'.$model->filename, $destinationPath.$model->filename);
            }

            if (\File::exists($destinationPath.$model->filename)) {
                $mimeType = \File::mimeType($destinationPath.$model->filename);
                $model->mime = substr($mimeType, strrpos($mimeType, '/') + 1);
                $model->type = File::categorizeTypeOfFile($model->filename, $mimeType);
                \Log::info('storing a '.$model->type_category);
            }

            return true;
        });

        static::saved(function ($model) {
            $model->flushCache($model);
            $model->flushRelatedCache($model);
        });
        static::updated(function ($model) {
        });

        static::deleting(function ($model) {
            if ($model->previousVersion) {
                $model->previousVersion->delete();
            }

            Storage::delete($model->filename);
        });

        static::deleted(function ($model) {
            $model->flushCache($model);
            $model->flushRelatedCache($model);

            Storage::delete($model->filename);
        });
    }

    /********************************Cache Clear Interface Method Implementation***************************/

    /**
     * Clear cache of this model.
     *
     * @return null
     */
    public function flushCache($fromModel = null)
    {
    }

    /**
     * Clear cache of models with caching related to this model.
     *
     * @return null
     */
    public function flushRelatedCache($fromModel = null)
    {
    }

    /*************************************** Custom Methods ***/

    public function version(FileUploadHelper $fileHelper)
    {
        $oldVersion = $this->replicate(['id', 'slug']);

        $this->filename = $fileHelper->getFileName();

        $oldVersion->replacement_id = $this->id;

        $this->fileable->files()->save($oldVersion); //A new file

        return $oldVersion;
    }

    public static function routes($model = 'motion')
    {
        \Route::get($model.'/{'.$model.'}/file/{file}/download', 'FileController@download');
        \Route::get($model.'/{'.$model.'}/file/{file}/resize/{width?}/{height?}', 'FileController@resize');
        \Route::resource($model.'/{'.$model.'}/file', 'FileController', ['except' => ['create', 'edit']]);
    }

    /**************************************** Getters and Setters ****************************************/

    public function setFilenameAttribute($filename)
    {
        $this->attributes['filename'] = $filename;
    }

    public function setFolderAttribute($folder)
    {
        $folder = snake_case($folder);
        $this->attributes['folder'] = $folder;
    }

    public function getLocationAttribute()
    {
        if ($this->folder) {
            $path = 'app/'.$this->folder;
        }

        return storage_path('app/'.$this->folder.'/'.$this->filename);
    }

    public function getPublicFilenameAttribute()
    {
        $name = str_slug($this->title);
        if (!$name) {
            $name = 'download_'.$this->type.'.'.\File::extension($this->filename);
        }

        return $name;
    }

    /**
     * Checks to see if the file is immediately or distantly the child of an object.
     *
     * @param ApiModel $parent thing to check relation with
     *
     * @return bool If this is related to the parent or not
     */
    public function isAssociatedWith($parent)
    {
        if (!$this->fileable) {
            return false; //Can't be associated with anything
        }

        //Direct relation (hero image/avatar)
        if ($this->fileable->getModelKey() == $parent->getModelKey()) {
            return true;
        }

        return false;
    }

    public static function categorizeTypeOfFile(String $filename, String $mimeType)
    {
        $type_category = substr($mimeType, 0, strrpos($mimeType, '/'));

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $documents = ['doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx'];
        $archives = ['zip', 'rar'];

        if (in_array($extension, $documents)) {
            return $type_category = 'document';
        }
        if (in_array($extension, $archives)) {
            return $type_category = 'archive';
        } else {
            return $type_category;
        }
    }

    public function resize($width = null, $height = null)
    {
        return Images::cache(function ($image) use ($width, $height) {
            return $image->make($this->location)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }, 20, true);
    }

/***************************SCOPE FUNCTIONS ***********************************************************************/


    /**************************************** Defined Relationships ****************************************/

    /**
     * The things files are attached to, at this stage almost always one image.
     *
     * @return Image The image this file is attached to
     */
    public function fileable()
    {
        return $this->morphTo();
    }

    /**
     * Every file has either an owner or uploader.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function userIdentification()
    {
        return $this->belongsTo('App\User', 'government_identification_id');
    }

    public function userAvatar()
    {
        return $this->belongsTo('App\User', 'avatar_id');
    }

    public function previousVersion()
    {
        return $this->hasOne('App\File', 'replacement_id');
    }
}
