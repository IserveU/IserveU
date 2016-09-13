<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Validator;

use App\Events\CommentVoteUpdated;
use App\Events\CommentVoteCreated;
use App\Events\CommentVoteDeleted;

use Auth;

use App\Repositories\Contracts\CachedModel;

class CommentVote extends NewApiModel implements CachedModel {

	use SoftDeletes;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var String
	 */
	protected $table = 'comment_votes';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var Array
	 */
	protected $fillable = ['position','vote_id','comment_id']; 


	/**
	 * The default attributes included in the JSON/Array
	 * @var Array
	 */
	protected $visible = [''];


	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var Array
	 */	
    protected $appends = [];  

 
	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();
		/* validation required on new */		
		static::creating(function($model){
			event(new CommentVoteCreated($model));
			return true;
		});

		static::updating(function($model){
			event(new CommentVoteUpdated($model));
			return true;
		});

		static::deleted(function($model){
			event(new CommentVoteDeleted($model));
			return true;
		});		
	}


	//////////////////////// Caching Implementation

    /**
     * Remove this items cache and nested elements
     * 
     * @param  Model $fromModel The model calling this (if exists)
     * @return null
     */

    public function flushCache($fromModel = null){


    	
    	\Cache::flush(); //Just for now
    }

    /**
     * Clears the caches of related models or there relations if needed
     * 
     * @param  Model $fromModel The model calling this (if exists)
     * @return null
     */
    public function flushRelatedCache($fromModel = null){
    	\Cache::flush(); //Just for now
    }



    public function setVisibility(){
        //If self or show-other-private-user
        if(Auth::check() && (Auth::user()->id==$this->vote->user->id || Auth::user()->hasRole('administrator'))){
            $this->skipVisibility();
        }
        
        return $this;
    }


	/************************************* Custom Methods *******************************************/
	
	
	/************************************* Getters & Setters ****************************************/
	
	/************************************* Casts & Accesors *****************************************/

	/************************************* Scopes ***************************************************/

	/**
	 * Gets comment votes on comment of a certain position
	 * @param  Builder $query    
	 * @param  Integer $position The position held
	 * @return Builder
	 */
	public function scopeOnCommentsOfPosition($query,$position){
		return $query->whereHas('comment.vote',function($q) use ($position){
			$q->where('position',$position);
		});
	}

	/**
	 * Comment votes not from a user
	 * @param  Builder $query
	 * @param  Integer $userId The id of a user who has made comment vottes
	 * @return [type]         [description]
	 */
	public function scopeNotUser($query,$userId){
		return $query->whereHas('vote',function($q) use ($userId){
			$q->where('user_id',$userId);
		});
	}

	public function scopeBetweenDates($query,$startDate,$endDate){
		if($startDate)	$query = $query->where('created_at','>=',$startDate);
		
		if($endDate) $query = $query->where('created_at','<=',$endDate);

		return $query;
	}

	public function scopeOnMotion($query,$motionId){
		return $query->whereHas('vote', function($q)  use ($motionId){
			$q->where('motion_id',$motionId);
		});
	}

	public function scopeByUser($query,$userId){
		return $query->whereHas('vote', function($q)  use ($userId){
			$q->where('user_id',$userId);
		});
	}

	/************************************* Relationships ********************************************/

	public function vote(){
		return $this->belongsTo('App\Vote');
	}

	public function comment(){	
		return $this->belongsTo('App\Comment');
	}

	public function scopeAgree($query){
        return $query->where('position','1');
    }
}
