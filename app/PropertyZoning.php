<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyZoning extends Model {

	public function properties(){
		return $this->hasMany('App\Property');
	}


}
