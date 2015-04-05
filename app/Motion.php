<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Motion extends Model {

	public function motion(){
		return $this->belongsTo('App\User');
	}

	public function comments(){
		return $this->hasMany('App\Comment');
	}

	public function votes(){
		return $this->hasMany('App\Vote');
	}

}
