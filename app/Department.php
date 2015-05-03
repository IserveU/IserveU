<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {

	
	public function motions(){
		return $this->hasMany('App\Motion');
	}


}
