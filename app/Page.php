<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends ApiModel
{

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
        'title', 'slug', 'content'
    ];

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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

   /**
     * Require default attribute values
     *
     * @var array
     */

    protected $attributes = [

    ];

    /**************************************** Data Mutators ****************************************/


    public function setTitleAttribute($input)
    {
    	$this->attributes['title'] = $input;
        $this->attributes['slug'] = str_slug($input);
    }

    public function setContentAttribute($input)
    {
    	$this->attributes['content'] = $input;
    }

	/********************************** Defined Relationships ***************************************/

	public function file()
	{
		return $this->hasMany('App\File');
	}

}
