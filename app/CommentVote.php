<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;

use App\Events\CommentVoteUpdated;
use App\Events\CommentVoteCreated;
use App\Events\CommentVoteDeleted;



class CommentVote extends NewApiModel {

	use SoftDeletes, Eloquence, Mappable;

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
	protected $visible = ['position','comment_id','id','comment_position'];


	/**
	 * The mapped attributes for 1:1 relations
	 * @var array
	 */ 
  	protected $maps = [
     	//'vote' 				=> 	['user_id','motion_id'],
     	'comment_position'	=>	'comment.vote.position',
     	'comment_user_id'	=>	'comment.vote.user_id'
    ];

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var Array
	 */	
    protected $appends = ['user_id','motion_id','comment_position'];  

    /**
     * The rules for all the variables
     * @var Array
     */  
	protected $rules = [
        'comment_id' 	=>	'integer|exists:comments,id|unique_with:comment_votes,vote_id',
        'position'		=>	'integer|min:-1|max:1|required', //Always required
        'id'			=>	'integer',
        'vote_id'		=>	'integer|exists:votes,id'
	];

	/**
	 * The attributes required on an update
	 * @var Array
	 */
	protected $onUpdateRequired = ['id','position'];

	/**
	 * The attributes required when creating the model
	 * @var Array
	 */
	protected $onCreateRequired = ['comment_id','vote_id'];
	
	/**
	 * The attributes that are unique so that the exclusion can be put on them on update validation
	 * @var array
	 */
	protected $unique = ['comment_id']; //Not actually unique, it's a 'unique with' vote_id

	
	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	protected $locked = [];

	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();
		/* validation required on new */		
		static::creating(function($model){
			event(new CommentVoteCreated($model));
			return $model->validate();	
		});

		static::updating(function($model){
			event(new CommentVoteUpdated($model));
			return $model->validate();			
		});

		static::deleted(function($model){
			event(new CommentVoteDeleted($model));
			return true;
		});		
	}

	/************************************* Custom Methods *******************************************/
	
	
	/************************************* Getters & Setters ****************************************/
	
	/************************************* Casts & Accesors *****************************************/

	/************************************* Scopes ***************************************************/

	/**
	 * [scopeCommentsOfPosition description]
	 * @param  [type] $query    [description]
	 * @param  [type] $position [description]
	 * @return [type]           [description]
	 */
	public function scopeOnCommentsOfPosition($query,$position){
		return $query->where('comment_position','=',$position);
	}

	public function scopeNotUser($query,$user_id){
		return $query->where('comment_user_id','!=',$user_id);
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
