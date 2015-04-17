<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Motion extends Model {

	protected $table = 'motions';


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','text'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['created_at','updated_at'];


	public function user(){
		return $this->belongsTo('App\User');
	}

	public function comments(){
		return $this->hasMany('App\Comment')->select(['id','text','motion_id']); //Trying to hide the userid
	}

	public function votes(){
		return $this->hasMany('App\Vote')->select(['id','motion_id','position']); //Trying to hide the userid so you can't find out an ID and then figure out their voting record
	}

	
	public function scopeActive($query){
		return $query->where('active',1);
	}

	public function scopeExpired($query){
		return $query->where('closing', '<=', new DateTime('NOW'));
	}

	public function scopeCurrent($query){
		return $query->where('closing', '>=', new DateTime('NOW'));
	}

	public function scopePassing($query){
		return $query->votes->where('position',1);
	}

	public function scopeFailing($query){
		return $query->votes->where('position',-1);
	}

	public function scopeAbstaining($query){
		return $query->votes->where('position',0);
	}

}
