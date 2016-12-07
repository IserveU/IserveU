<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileCategory extends ApiModel
{
    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'file_categories';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes fillable by the administrator of this model.
     *
     * @var array
     */
    protected $adminFillable = ['id', 'name'];

    /**
     * The attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $visible = ['id', 'name'];

    /**
     * The attributes visible to an administrator of this model.
     *
     * @var array
     */
    protected $adminVisible = ['id', 'name'];

    /**
     * The attributes visible to the user that created this model.
     *
     * @var array
     */
    protected $creatorVisible = ['id', 'name'];

    /**
     * The attributes appended and returned (if visible) to the user.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The rules for all the variables.
     *
     * @var array
     */
    protected $rules = [
        'name'            => 'min:1|unique:file_categories,name',
        'id'              => 'integer',
    ];

    /**
     * The variables that are required when you do an update.
     *
     * @var array
     */
    protected $onUpdateRequired = ['id'];

    /**
     * The variables requied when you do the initial create.
     *
     * @var array
     */
    protected $onCreateRequired = ['name'];

    /**
     * Fields that are unique so that the ID of this field can be appended to them in update validation.
     *
     * @var array
     */
    protected $unique = ['name'];

    /**
     * The front end field details for the attributes in this model.
     *
     * @var array
     */
    protected $fields = [
        'name'        => ['tag' => 'md-switch', 'type' => 'X', 'label' => 'Attribute Name', 'placeholder' => ''],
    ];

    /**
     * The fields that are locked. When they are changed they cause events like resetting people's accounts.
     *
     * @var array
     */
    protected $locked = [];

    /**************************************** Standard Methods **************************************** */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->validate()) {
                return false;
            }

            return true;
        });

        static::created(function ($model) {
            $result = \File::makeDirectory(getcwd().'/uploads/'.$model->name);

            return $result;
        });

        static::updating(function ($model) {
            if (!$model->validate()) {
                return false;
            }

            return true;
        });
    }

    /************************************* Custom Methods *******************************************/

    /************************************* Getters & Setters ****************************************/

    /************************************* Casts & Accesors *****************************************/

    /************************************* Scopes ***************************************************/

    /************************************* Relationships ********************************************/

    public function files()
    {
        return $this->hasMany('App\File');
    }
}
