<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\CommentVote;
use DB;
use Illuminate\Support\Facades\Validator;
use Request;

use Carbon\Carbon;

use App\Events\Comment\CommentDeleted;

use App\Events\Comment\CommentUpdated;
use App\Events\Comment\CommentCreated;

use Auth;

class Comment extends NewApiModel {
	

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var String
	 */
	protected $table = 'comments';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var Array
	 */
	protected $fillable = ['text','vote_id'];


	/**
	 * The default attributes included in the JSON/Array
	 * @var Array
	 */
	protected $visible = ['text','vote','id','commentRank','created_at','updated_at','user_id']; //The user model guards this, but it must be included (If this gives too much, try just removing user_id)
	
	
	protected $with = ['vote','commentRank'];


	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var Array
	 */	
    protected $appends = ['position','motion_id','commentRank'];  


	/**
	 * The fields that are dates/times
	 * @var array
	 */
	protected $dates = ['created_at','updated_at'];


	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();

		static::creating(function($model){
			event(new CommentCreated($model));
			return true;
		});

		static::updating(function($model){
			event(new CommentUpdated($model));
			return true;
		});

		static::deleting(function($model){
			if($model->commentVotes){
				//Database is doing this
			}
			return true;
		});

		static::deleted(function($model){
			event(new CommentDeleted($model));
			return true;
		});
	}


    public function skipVisibility(){
       $this->setVisible(array_merge(array_keys($this->attributes)));
    }

    public function setVisibility(){

        //If self or show-other-private-user
        if(Auth::check() && (Auth::user()->id==$this->id || Auth::user()->hasRole('administrator'))){
            $this->skipVisibility();
        }

        if($this->publiclyVisible){
			$this->setVisible(['user','last_name','id','community_id']);
        }


		$this->setVisible(['text','created_at','vote_id','id']);



        return $this;
    }



	/**************************************** Custom Methods **************************************** */
	


	
	/****************************************** Getters & Setters ************************************/



	/**
	 * @return integer the mutator to get the sum of all the comment votes
	 */
	public function getCommentRankAttribute()
	{
	  // if relation is not loaded already, let's do it first
	  if ( ! array_key_exists('commentRank', $this->relations)) 
	    $this->load('commentRank');
	 
	  $related = $this->getRelation('commentRank');
	 
	  // then return the count directly
	  return ($related) ? (int) $related->rank : 0;
	}

	/************************************* Casts & Accesors *****************************************/
	
	/**
	 * @return The sum of all the comment votes 
	 */
	public function commentRank()
	{
	  return $this->hasOne('App\CommentVote')
	    ->selectRaw('comment_id, sum(position) as rank')
	    ->groupBy('comment_id');
	}

	/************************************* Scopes *****************************************/



	public function scopePosition($query,$position){
		return $query->whereHas('vote',function($q) use ($position) {
			$q->where('position',$position);
		});
	}


	public function scopeBetweenDates($query,$startDate,$endDate){
		if($startDate)	$query = $query->where('created_at','>=',$startDate);
		if($endDate) $query = $query->where('created_at','<=',$endDate);
		
		return $query;
	}

	public function scopeOrderBy($query,$field,$direction){
		return $query->orderBy($field,$direction);
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

	/**********************************  Relationships *****************************************/
	public function commentVotes(){
		return $this->hasMany('App\CommentVote');
	}

	public function vote(){	//The user who cast a vote that then was allowed to comment on that side
		return $this->belongsTo('App\Vote');
	}


}
