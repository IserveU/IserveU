<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use App\CommentVote;
use DB;
use Illuminate\Support\Facades\Validator;
use Request;

use Carbon\Carbon;

use App\Events\Comment\CommentDeleted;

use App\Events\Comment\CommentUpdated;
use App\Events\Comment\CommentCreated;



class Comment extends NewApiModel {
	
	use SoftDeletes, Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var String
	 */
	protected $table = 'comments';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var Array
	 */
	protected $fillable = ['text'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var Array
	 */
	protected $adminFillable = [];

	/**
	 * The default attributes included in the JSON/Array
	 * @var Array
	 */
	protected $visible = ['text','vote','user','id','commentRank', 'created_at', 'updated_at']; //The user model guards this, but it must be included (If this gives too much, try just removing user_id)
	
	/**
	 * The attributes visible to an administrator of this model
	 * @var Array
	 */
	protected $adminVisible = [];

	/**
	 * The attributes visible to the user that created this model
	 * @var Array
	 */
	protected $creatorVisible = [];

	/**
	 * The attributes visible if the entry is marked as public
	 * @var array
	 */
	protected $publicVisible =  [];

	/**
	 * The mapped attributes for 1:1 relations
	 * @var array
	 */
   	protected $maps = [
     	'vote' 			=> 	['motion_id','position','user','user_id'] /* User ID is here for the benefit of the getMotionComments */
    ];
	
	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var Array
	 */	
    protected $appends = ['position','motion_id','commentRank','user'];  


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

		static::deleted(function($model){
			event(new CommentDeleted($model));
			return true;
		});
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

	public function scopeAgree($query){
		return $query->where('position',1);
	}

	public function scopeDisagree($query){
		return $query->where('position','!=',1);
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
