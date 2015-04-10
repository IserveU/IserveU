<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	
	public function roles(){
		return $this->belongsToMany('App\Roles');
	}

}
