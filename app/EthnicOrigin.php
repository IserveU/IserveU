<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class EthnicOrigin extends Model {

	public function users(){	
		return $this->hasMany('App\User');
	}

}
