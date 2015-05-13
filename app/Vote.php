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
	protected $hidden = [];


	protected $table = 'votes';



	public function user(){
		return $this->belongsTo('App\User');
	}

	public function motion(){
		return $this->belongsTo('App\Motion');
	}


}
