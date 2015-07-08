<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use App\CommentVote;
use DB;
use Illuminate\Support\Facades\Validator;
use Request;


class Comment extends ApiModel {
	
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
	protected $visible = ['text','vote','user','id']; //The user model guards this, but it must be included (If this gives too much, try just removing user_id)
	
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
     * The rules for all the variables
     * @var Array
     */
	protected $rules = [
        'text'			=>	'min:3|string',
        'vote_id'		=>	'integer|exists:votes,id|unique:comments,vote_id',
        'id'			=>	'integer'
	];

	/**
	 * The attributes required on an update
	 * @var Array
	 */
	protected $onUpdateRequired = ['id'];

	/**
	 * The attributes required when creating the model
	 * @var Array
	 */
	protected $onCreateRequired = ['text'];
	
	/**
	 * The attributes that are unique so that the exclusion can be put on them on update validation
	 * @var array
	 */
	protected $unique = ['vote_id'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'text' 					=>	['tag'=>'textarea','type'=>'textarea','label'=>'','placeholder'=>'Your comment on this motion'],
		//? 'vote_id' 					=>	['tag'=>'hidden','type'=>'hidden','label'=>'','placeholder'=>''],
		// 'id' 					=>	['tag'=>'hidden','type'=>'hidden','label'=>'','placeholder'=>'']
	];

	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	private $locked = [];


	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();
		/* validation required on new */		
		static::creating(function($model){
			return $model->validate();	
		});

		static::updating(function($model){

			return $model->validate();			
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


	/**********************************  Relationships *****************************************/
	public function commentVotes(){
		return $this->hasMany('App\CommentVote');
	}

	public function vote(){	//The user who cast a vote that then was allowed to comment on that side
		return $this->belongsTo('App\Vote');
	}


}
