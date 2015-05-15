<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	public function motions(){
		return $this->hasMany('App\Motion');
	}

}
