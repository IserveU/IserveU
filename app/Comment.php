<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Comment extends Model {
	
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['motion_id','text','vote_id','created_at'];


	/**
	 * The attributes excluded from the model's JSON form. The general pubilc shouldn't be able to see non-public users names
	 *
	 * @var array
	 */
	protected $hidden = ['user_id','created_at','deleted_at','updated_at'];


	protected $table = 'comments';


	public function commentVotes(){
		return $this->hasMany('App\CommentVote');
	}

	public function vote(){	//The user who cast a vote that then was allowed to comment on that side

		return $this->belongsTo('App\Vote');

	}

	public function motion(){	//The motion that this comment is attached to
		return $this->belongsTo('App\Motion');
	}

/*	public function user(){
	 	return $this->vote->user; Can't get this to work, need belongsToManyThough but it d
	} */


}
