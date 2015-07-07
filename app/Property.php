<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends ApiModel {

	public function propertyBlock(){
		return $this->belongsTo('App\PropertyBlock');
	}

	public function propertyDescription(){
		return $this->belongsTo('App\PropertyDescription');
	}

	public function propertyZone(){
		return $this->belongsTo('App\PropertyZone');
	}

	public function propertyPollDivision(){
		return $this->belongsTo('App\PropertyPoleDivision');
	}

	public function propertyPlan(){
		return $this->belongsTo('App\PropertyPlan');
	}

	public function propertyAssesments(){
		return $this->hasMany('App\PropertyAssesment');
	}

	public function users(){
		return $this->belongsToMany('App\User');
	}



}
