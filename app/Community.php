<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Community extends NewApiModel
{
    use Sluggable;

    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'communities';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['name', 'active', 'adjective'];

    /**
     * The attributes universally visible.
     *
     * @var array
     */
    protected $visible = ['id', 'name', 'active', 'slug', 'adjective'];

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
}
