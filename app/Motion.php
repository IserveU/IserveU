<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Motion extends Model {

	protected $table = 'motions';


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','active','text'];

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
		return $this->hasMany('App\Comment');
	}

	public function votes(){
		return $this->hasMany('App\Vote');
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
		return $query->where('pro_tally','>','con_tally');
	}

	public function scopeFailing($query){
		return $query->where('pro_tally','<=','con_tally');

	}

}
