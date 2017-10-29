<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Department extends NewApiModel
{
    use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['name', 'active'];

    /**
     * The relations commonly used.
     *
     * @var array
     */
    protected $with = [];

    /**
     * The attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $visible = ['name', 'active', 'id', 'slug', 'icon'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source'   => ['name'],
                'onUpdate' => true,
            ],
        ];
    }

    /**************************************** Standard Methods **************************************** */

    public static function boot()
    {
        parent::boot();
    }

    /**************************************** Custom Methods **************************************** */

    /****************************************** Getters & Setters ************************************/

    /************************************* Casts & Accesors *****************************************/

    /************************************* Scopes *****************************************/

    /**********************************  Relationships *****************************************/

    public function motions()
    {
        return $this->hasMany('App\Motion');
    }

    public function delegations()
    {
        return $this->hasMany('App\Delegations');
    }
}
