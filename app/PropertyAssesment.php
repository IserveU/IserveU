<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyAssesment extends Model {

	public function property(){
		return $this->belongsTo('App\Property');
	}

}
