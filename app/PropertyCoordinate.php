<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyCoordinate extends ApiModel {

	public function propertyBlock(){
		return $this->belongsTo('App\PropertyBlock');
	}

}
