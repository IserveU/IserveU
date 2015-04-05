<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function motion(){
		return $this->belongsTo('App\Motion');
	}


}
