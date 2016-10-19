<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Community extends ApiModel
{
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
    protected $fillable = ['name', 'active'];
}
