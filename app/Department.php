<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Validator;
use Request;
use Cviebrock\EloquentSluggable\Sluggable;




class Department extends NewApiModel{


	use Sluggable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'departments';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['name', 'active'];

	/**
	 * The relations commonly used
	 * @var array
	 */
	protected $with = [];


	
	/**
	 * The attributes included in the JSON/Array
	 * @var array
	 */
	protected $visible = ['name','active','id'];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['name'],
            	'onUpdate'	=> true
            ]
        ];
    }


	/**************************************** Standard Methods **************************************** */

	public static function boot(){
		parent::boot();

	}


	/**************************************** Custom Methods **************************************** */
  
 

	/****************************************** Getters & Setters ************************************/

	
	/************************************* Casts & Accesors *****************************************/

	/************************************* Scopes *****************************************/


	/**********************************  Relationships *****************************************/


	public function motions(){
		return $this->hasMany('App\Motion');
	}

	public function delegations(){
		return $this->hasMany('App\Delegations');
	}

}
