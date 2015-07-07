<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Support\Facades\Validator;


class CommentVote extends ApiModel {

	use SoftDeletes, Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var String
	 */
	protected $table = 'comment_votes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['position','comment_id','vote_id'];
	protected $adminFillable = [];

	/**
	 * The attributes excluded from the model's JSON form. The general pubilc shouldn't be able to see non-public users identifiable details or IDs at any point
	 *
	 * @var array
	 */
	protected $visible = ['position','comment_id','id'];
	protected $adminVisible = [];
 
  	protected $maps = [
     	'vote' 			=> 	['user_id','motion_id']
    ];

    protected $appends = ['user_id','motion_id'];  

  
	protected $rules = [
        'comment_id' 	=>	'integer|exists:comments,id|unique_with:comment_votes,vote_id',
        'position'		=>	'integer|min:-1|max:1|required', //Always required
        'id'			=>	'integer',
        'vote_id'		=>	'integer|exists:votes,id'
	];



	/**************************************** Standard Methods **************************************** */
	public static function boot(){
		parent::boot();
		/* validation required on new */		
		static::creating(function($model){
			$model->setRules();
			return $model->validate();	
		});

		static::updating(function($model){
			$model->setRules();
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


	/************************************* Custom Methods *******************************************/
	
	
	/************************************* Getters & Setters ****************************************/
	
	public function setRules($required = NULL){
		if($required){
			return $this->rules = AddRequired($this->rules,$required);	 //At the outset, just need to have this
		}

		//Otherwise automatically detect if this is a store or an update
		if($this->id){
			$this->rules['comment_id'] = $this->rules['comment_id'].",".$this->id; //Otherwise the validator detects a duplicate (of itself)
			return $this->rules = AddRequired($this->rules,['id','position']);	 //Is an update
		}
		return $this->rules =  AddRequired($this->rules,['vote_id','comment_id']); //Is a store
	}


	/************************************* Casts & Accesors *****************************************/

	/************************************* Scopes ***************************************************/

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
