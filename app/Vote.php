<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;
use App\Events\VoteUpdated;
use App\Events\VoteCreated;
use App\Events\VoteDeleting;

use Auth;
use Carbon\Carbon;
use Cache;

class Vote extends NewApiModel {

	use SoftDeletes, Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var String
	 */
	protected $table = 'votes';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var Array
	 */
	protected $fillable = ['motion_id','position','user_id'];



	/**
	 * The default attributes included in the JSON/Array
	 * @var Array
	 */
	protected $visible = [];

	

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var Array
	 */	
    protected $appends = [];



	/**************************************** Standard Methods *****************************************/
	public static function boot(){
		parent::boot();
		/* validation required on new */		
		static::creating(function($model){
			return true;	
		});

		static::created(function($model){
			return true;
		});

		static::updating(function($model){
			return true;
		});

		static::updated(function($model){
			event(new VoteUpdated($model));
			return true;
		});

		static::deleting(function($model){
			if($this->comment){
				$this->comment->delete();
			}
 			
			return true;
		});	

	}

    public function setVisibility(){

        //If self or show-other-private-user
        if(Auth::check() && Auth::user()->id==$this->user_id){
            $this->setVisible(['id','position','motion_id','user_id','deferred_to_id','visited']);
        }

        if($this->user->publiclyVisible){
			$this->setVisible(['id','position','motion_id','id','deferred_to_id']);
        }


        return $this;
    }

	/************************************* Custom Methods *******************************************/
	

	
	/************************************* Getters & Setters ****************************************/
	

	public function setPositionAttribute($value){
		if(Auth::check() && Auth::user()->id == $this->user_id){
			$this->attributes['deferred_to_id']		=	NULL;
		}
		$this->attributes['position'] 				= 	$value;
	}


	/************************************* Casts & Accesors *****************************************/


	
	/************************************* Scopes ***************************************************/

	public function scopeActive($query){
		return $query->whereNull('deferred_to_id');
	}

	public function scopePassive($query){
		return $query->whereNotNull('deferred_to_id');
	}

	public function scopeAgree($query){
		return $query->where('position',1);
	}

	public function scopeDisagree($query){
		return $query->where('position',-1);
	}

	public function scopeAbstain($query){
		return $query->where('position',0);
	}

	public function scopeCast($query){
		return $query->whereNotNull('position');
	}

	public function scopeOnActiveMotion($query){
		return $query->whereHas('motion',function($query){
			$query->where('active',1);
		});
	}


	/************************************* Relationships ********************************************/

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function motion(){
		return $this->belongsTo('App\Motion');
	}

	public function comment(){
		return $this->hasOne('App\Comment');
	}

	public function deferred(){
		return $this->belongsTo('App\User','deferred_to_id');
	}
}