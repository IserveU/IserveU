<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class Page extends NewApiModel
{
    use SluggableScopeHelpers, Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'text',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'text',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'content',
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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source'    => ['title'],
                'onUpdate'  => true,
            ],
        ];
    }

    /**
     * Require default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'title'     => 'New Page',
        'content'   => '{"text": ""}',
    ];


    /**
     * Casts fields to database columns.
     *
     * @var array
     */
    protected $casts = [
        'content'   => 'array',
    ];

    /**************************************** Data Mutators ****************************************/

    /**
     * Sets the JSON field.
     *
     * @param string $input content of the text field
     */
    public function setTextAttribute($input)
    {
        $this->content = array_merge($this->content, ['text' => $input]);
    }

    /**
     * Sets the JSON field.
     *
     * @param string $input content of the text field
     */
    public function getTextAttribute()
    {
        return $this->content['text'];
    }

    /********************************** Defined Relationships ***************************************/

    public function file()
    {
        return $this->hasMany('App\File');
    }
}
