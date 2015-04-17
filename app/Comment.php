<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {
	
	use SoftDeletes;


	/**
	 * The attributes excluded from the model's JSON form. The general pubilc shouldn't be able to see non-public users names
	 *
	 * @var array
	 */
	protected $hidden = ['user_id','created_at','deleted_at','updated_at'];



	public function commentVotes(){
		return $this->hasMany('App\CommentVote');
	}

	public function vote(){	//The user who cast a vote that then was allowed to comment on that side
		return $this->belongsTo('App\Vote');
	}

	public function motion(){	//The motion that this comment is attached to
		return $this->belongsTo('App\Motion');
	}

	public function user(){
		//has through vote
	}


}
