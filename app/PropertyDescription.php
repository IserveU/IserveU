<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyDescription extends Model {

	public function properties(){
		return $this->hasMany('App\Property');
	}	

}
