<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Motion extends Model {

	protected $table = 'motions';

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function comments(){
		return $this->hasMany('App\Comment');
	}

	public function votes(){
		return $this->hasMany('App\Vote');
	}

}
