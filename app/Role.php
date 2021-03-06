<?php

namespace App;

use Cache;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name', 'description',
    ];

    public static function called($name)
    {
        return Cache::tags('role')->remember('role.'.$name, 120, function () use ($name) {
            return Role::where('name', '=', $name)->first();
        });
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            Cache::tags('role')->flush();

            return true;
        });

        static::updated(function ($model) {
            Cache::tags('role')->flush();

            return true;
        });
    }

    /**************************************** Defined Relationships ****************************************/
    public function users()
    {
        return $this->belongsToMany('App\User'); //,'users'
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission');
    }

    /*********************************** Other methods *******************************************/
}
