<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Role;
use Auth;
use Hash;
use Request;
use App\Events\UserUpdated;
use App\Events\UserCreated;
use Event;
use Mail;

class User extends ApiModel implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, SoftDeletes, EntrustUserTrait;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['email','ethnic_origin_id','public','password','first_name','middle_name','last_name','date_of_birth','public','website'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = ['identity_verified'];

	/**
	 * The default attributes included in any JSON/Array
	 * @var array
	 */
	protected $visible = ['public'];

	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = ['first_name','last_name','middle_name','email','ethnic_origin_id','date_of_birth','public','id','login_attempts','created_at','updated_at','identity_verified','permissions','votes'];

	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = ['first_name','last_name','middle_name','email','ethnic_origin_id','date_of_birth','public','id','permissions','votes'];

	/**
	 * The attributes visible if the entry is marked as public
	 * @var array
	 */
	protected $publicVisible =  ['first_name','last_name','public','id','votes'];

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['permissions'];

    /**
     * The rules for all the variables
     * @var array
     */
	protected $rules = [	
		'email' 				=>	'email|unique:users,email',
	    'password'				=>	'min:8',
	    'first_name'			=>	'string',
	    'middle_name'			=>	'string',
	    'last_name'				=>	'string',
	    'ethnic_origin_id'		=>	'integer|exists:ethnic_origins,id',
	    'date_of_birth'			=>	'date',
	    'public'				=>	'boolean',
        'id'       				=>	'integer',
	    'login_attempts'		=>	'integer',
	    'identity_verified'		=>	'boolean',
	    'remember_token'		=>	'unique:users,remember_token'
	];

	/**
	 * The variables that are required when you do an update
	 * @var array
	 */
	protected $onUpdateRequired = ['id'];

	/**
	 * The variables requied when you do the initial create
	 * @var array
	 */
	protected $onCreateRequired = ['email','password','first_name','last_name'];

	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['email', 'remember_token'];

	/**
	 * The front end field details for the attributes in this model 
	 * @var array
	 */
	protected $fields = [
		'email' 					=>	['tag'=>'input','type'=>'email','label'=>'Email Address','placeholder'=>'Email Address'],
	    'password'					=>	['tag'=>'input','type'=>'password','label'=>'Password','placeholder'=>'Your Password'],
	    'first_name'				=>	['tag'=>'input','type'=>'input','label'=>'First Name','placeholder'=>'First Name'],
	    'middle_name'				=>	['tag'=>'input','type'=>'input','label'=>'Middle Name','placeholder'=>'Middle Name'],
	    'last_name'					=>	['tag'=>'input','type'=>'input','label'=>'First Name','placeholder'=>'Last Name'],
	    'ethnic_origin_id'			=>	['tag'=>'md-select','type'=>'select','label'=>'Ethnic Origin','placeholder'=>'Primary Ethnic Origin'],
	    'date_of_birth'				=>	['tag'=>'input','type'=>'date','label'=>'Date of Birth','placeholder'=>'Date of Birth'],
	    'public'					=>	['tag'=>'md-switch','type'=>'md-switch','label'=>'Public','placeholder'=>'Enable Public Profile'],
	    'identity_verified'			=>	['tag'=>'md-switch','type'=>'md-switch','label'=>'Identity Verified','placeholder'=>'User Is Verified'],
	];
	
	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	private $locked = ['first_name','middle_name','last_name','date_of_birth'];


	/**************************************** Standard Methods **************************************** */

	public static function boot(){
		parent::boot();

		/* validation required on new */		
		static::creating(function($model){
			if(!$model->validate()) return false;
			event(new UserCreated($model));
			return true;
		});

		static::updating(function($model){
			if(!$model->validate()) return false;
			event(new UserUpdated($model));
			return true;
		});
	}


	/**************************************** Custom Methods **************************************** */
    
	/**
	 * @param Adds the named role to a user
	 */
    public function addUserRoleByName($name){
	    $userRole = Role::where('name', '=', $name)->firstOrFail();
	    $this->roles()->attach($userRole->id);
    }


    public function getFillableAttribute(){
        if(!Auth::check()){ //If not logged in, don't go to parent
			return $this->fillable;
        }
        return parent::getFillableAttribute();
    }

	/****************************************** Getters & Setters ************************************/

	/**
	 * @return Overrides the API Model, will see if it can be intergrated into it
	 */
	public function getVisibleAttribute(){
		if(!Auth::check()){
			return $this->visible;
		}

		if(Auth::user()->id==$this->id){ // Logged in user
			$this->visible = array_unique(array_merge($this->creatorVisible, $this->visible));
		} 

		if($this->public) { //Public profile
			$this->visible = array_unique(array_merge($this->publicVisible, $this->visible));
		}

		return parent::getVisibleAttribute();
	}

	/**
	 * @param string takes a string and hashes it into a password
	 */
	public function setPasswordAttribute($value){
		$this->attributes['password'] = Hash::make($value);
	}

	/**
	 * @return The permissions attached to this user through entrust
	 */
	public function getPermissionsAttribute(){
		$permissions = [];
		foreach ($this->roles as $role){
			$role_permissions = $role->perms()->get();
			foreach($role_permissions as $permission){
				$newarray = explode('-', $permission->name);
				$permissions[$newarray[1]][] = $newarray[0];
			}
		}
		return $permissions;
	}


	/************************************* Casts & Accesors *****************************************/



	/************************************* Scopes *****************************************/
   	public function scopeArePublic($query){
        return $query->where('public',1);
    }
    

    public function scopeWithEmail($query,$email){
    	return $query->where('email',$email);
    }

	/**********************************  Relationships *****************************************/


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