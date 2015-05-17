<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	protected $dates = ['time'];

	public function motions(){
		return $this->hasMany('App\Motion');
	}



}
