<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyAssesment extends ApiModel {

	public function property(){
		return $this->belongsTo('App\Property');
	}

}
