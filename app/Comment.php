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
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['text','vote_id'];
	protected $adminFillable = [];


	/**
	 * The attributes excluded from the model's JSON form. The general pubilc shouldn't be able to see non-public users identifiable details or IDs at any point
	 *
	 * @var array
	 */
	protected $visible = ['text','vote','user','id']; //The user model guards this, but it must be included (If this gives too much, try just removing user_id)
	protected $adminVisible = [];

	protected $table = 'comments';

   	protected $maps = [
     	'vote' 			=> 	['motion_id','position','user','user_id'] /* User ID is here for the benefit of the getMotionComments */
    ];

    protected $appends = ['position','motion_id','commentRank','user'];  


	protected $rules = [
        'text'			=>	'required',
        'vote_id'		=>	'integer|exists:votes,id|unique:comments,vote_id',
        'id'			=>	'integer'
	];

	public $errors;




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

	public function validate(){
		$validator = Validator::make($this->getAttributes(),$this->rules);
		if($validator->fails()){
			$this->errors = $validator->messages();
			return false;
		}	
		return true;
	}


	/**************************************** Custom Methods **************************************** */
	


	
	/****************************************** Getters & Setters ************************************/


	public function setRules($required = NULL){
		if($required){
			return $this->rules = AddRequired($this->rules,$required);	 //At the outset, just need to have this
		}

		//Automatically detect if this is a store or an update
		if($this->id){
			return $this->rules = AddRequired($this->rules,['id']);	 //Is an update
		}
		return $this->rules =  AddRequired($this->rules,['vote_id']); //Is a store
	}


	public function getRulesAttribute(){
		if($this->id){ //Existing record
			$this->rules['vote_id'] = $this->rules['vote_id'].','.$this->id;

			//$this->rules = AddRule($this->rules,['id'],'required');

			if(Request::method()=="PATCH"){ // Adds things that aren't actual validation rules if this is the actual patch
				return $this->rules;	
			}
		}

		if(Request::method()=="POST"){ //Initial create
		//	$this->rules = AddRule($this->rules,['email','first_name','last_name','password'],'required');
		//	return $this->rules; //DOn't add on things that aren't actual validation rules
		}

		return $this->rules;	
	}


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
