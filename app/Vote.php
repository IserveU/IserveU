<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vote extends Model {

	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['motion_id','position','user_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['user_id']; //If comments gets pulled we don't want people to figure out that the comment is the vote, and the vote is the user


	protected $table = 'votes';



	public function user(){
		return $this->belongsTo('App\User');
	}

	public function motion(){
		return $this->belongsTo('App\Motion');
	}


}
