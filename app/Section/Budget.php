<?php

namespace App\Section;

use Illuminate\Database\Eloquent\Model;

class Budget extends Section
{
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


    protected $attributes = array(
        'type' => 'Budget'
    );


}
