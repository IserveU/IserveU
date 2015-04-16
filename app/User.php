<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, SoftDeletes, EntrustUserTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 * administrator: 				We want to know if someone is becoming an administrator
	 * verified_until/property_id: 	If a property_id changes/we need to reverify the person
	 * hash/pasword:				Seems like these should be setup moremanually
	 *
	 * @var array
	 */
	protected $fillable = ['email','first_name','last_name','middle_name','ethnic_origin_id','date_of_birth','public','property_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['created_at', 'updated_at', 'password', 'remember_token','verified_until','administrator','property_id','email','ethnic_origin_id','login_attempts','locked_until'];



	public function ethnicOrigin(){
		return $this->belongsTo('App\EthnicOrigin');
	}

	public function motions(){
		return $this->hasMany('App\Motion');
	}

	public function votes(){
		return $this->hasMany('App\Vote');
	}

	public function comments(){
		return $this->hasManyThrough('App\Comment','App\Vote');
	}

	public function properties(){
		return $this->belongsToMany('App\Property');
	}



}
