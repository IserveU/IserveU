<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModification extends ApiModel
{
    use SoftDeletes;

    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'user_modifications';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['modification_to_id', 'modification_by_id', 'fields'];

    /**
     * The attributes fillable by the administrator of this model.
     *
     * @var array
     */
    protected $adminFillable = [];

    /**
     * The default attributes included in any JSON/Array.
     *
     * @var array
     */
    protected $visible = ['id'];

    /**
     * The attributes visible to an administrator of this model.
     *
     * @var array
     */
    protected $adminVisible = ['modification_to_id', 'modification_by_id', 'fields', 'created_at'];

    /**
     * The attributes visible to the user that created this model.
     *
     * @var array
     */
    protected $creatorVisible = ['modification_to_id', 'modification_by_id', 'fields', 'created_at'];

    /**
     * The attributes visible if the entry is marked as public.
     *
     * @var array
     */
    protected $publicVisible = [];

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
        'id'                 => 'integer',
        'modification_to_id' => 'integer|exists:users,id',
        'modification_by_id' => 'integer|exists:users,id',
        'fields'             => 'min:1',
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
    protected $onCreateRequired = ['modification_to_id', 'fields'];

    /**
     * Fields that are unique so that the ID of this field can be appended to them in update validation.
     *
     * @var array
     */
    protected $unique = [];

    /**
     * The front end field details for the attributes in this model.
     *
     * @var array
     */
    protected $fields = []; //This is an automatic model, not updated by admin or anything

    /**
     * The fields that are dates/times.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes).
     *
     * @var array
     */
    protected $locked = [];

    /**************************************** Standard Methods **************************************** */

    public static function boot()
    {
        parent::boot();

        /* validation required on new */
        static::creating(function ($model) {
            if (!$model->validate()) {
                return false;
            }

            return true;
        });

        static::created(function ($model) {
            return true;
        });

        static::updating(function ($model) {
            if (!$model->validate()) {
                return false;
            }

            return true;
        });
    }

    /**************************************** Custom Methods **************************************** */

    /****************************************** Getters & Setters ************************************/

    /************************************* Casts & Accesors *****************************************/

    /************************************* Scopes *****************************************/

    /**********************************  Relationships *****************************************/

 //    public function modifiedcationTo(){
    // 	return $this->hasMany('App\User','modification_to_id');
    // }

    // public function modifiedcationBy(){
    // 	return $this->hasMany('App\User','modification_by_id');
    // }
}
