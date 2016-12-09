<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EthnicOrigin extends ApiModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ethnic_origins';

    /**
     * The attributes that are mass assignable.
     * administrator: 				We want to know if someone is becoming an administrator
     * verified_until/property_id: 	If a property_id changes/we need to reverify the person
     * hash/pasword:				Seems like these should be setup moremanually.
     *
     * @var array
     */
    protected $fillable = ['region', 'description'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $visible = ['region', 'description', 'id'];

    protected $rules = [
        'region'                 => 'string|unique:ethnic_origins',
        'description'            => 'string|unique:ethnic_origins',
    ];

    public $fields = [
        'region'                     => ['tag' => 'input', 'type' => 'input', 'label' => 'Region', 'placeholder' => 'The region from'],
        'description'                => ['tag' => 'input', 'type' => 'input', 'label' => 'Password', 'placeholder' => 'Description of the region'],

    ];

    protected $locked = [];

    public function secureFill(array $input)
    {
        return parent::fill($input);
    }

    /**************************************** Standard Methods **************************************** */

    public static function boot()
    {
        parent::boot();

        /* validation required on new */
        static::creating(function ($model) {
            return $model->validate();
        });

        static::updating(function ($model) {
            return $model->validate();
        });
    }

    /**************************************** Custom Methods **************************************** */

    /****************************************** Getters & Setters ************************************/

    public function getVisibleAttribute()
    {
        return $this->visible;
    }

    public function getFillableAttribute()
    {
        return $this->fillable;
    }

    public function getRulesAttribute()
    {
        return $this->rules;
    }

    /************************************* Casts & Accesors *****************************************/
    public function toJson($options = 0)
    {
        $this->getVisibleAttribute();

        return parent::toJson();
    }

    public function toArray()
    {
        $this->getVisibleAttribute();

        return parent::toArray();
    }

    /************************************* Scopes *****************************************/

    /**********************************  Relationships *****************************************/

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
