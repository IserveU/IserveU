<?php 
namespace App;

//use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait

use Auth;
use Hash;
use Request;
use Carbon\Carbon;
use Redis;
use Event;
use Mail;
use DB;
use Setting;

use App\Role;

use App\Events\User\UserCreated;
use App\Events\User\UserCreating;
use App\Events\User\UserUpdated;
use App\Events\User\UserUpdating;
use App\Events\User\UserDeleted;

use Cviebrock\EloquentSluggable\Sluggable;

use App\Repositories\StatusTrait;

use Illuminate\Notifications\Notifiable;

class User extends NewApiModel implements AuthorizableContract, CanResetPasswordContract,Authenticatable {

	use Authorizable, CanResetPassword, AuthenticatableTrait, Notifiable, StatusTrait, Sluggable; //, StatusTrait;

	use EntrustUserTrait{
        // EntrustUserTrait::can as may; //There is an entrust collision here
        // Authorizable::can insteadof EntrustUserTrait;

        Authorizable::can as may; //There is an entrust collision here
        EntrustUserTrait::can insteadof Authorizable;
    }

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['email','ethnic_origin_id','password','first_name','middle_name','last_name','date_of_birth','public','website', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted', 'community_id','identity_verified', 'address_verified_until','preferences'];


	protected $visible = [''];



	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['permissions','totalDelegationsTo', 'user_role','avatar','need_identification'];

    protected $with = ['roles','community'];


	/**
	 * Fields that are unique so that the ID of this field can be appended to them in update validation
	 * @var array
	 */
	protected $unique = ['email', 'remember_token'];


	/**
	 * The fields that are dates/times
	 * @var array
	 */
	protected $dates = ['address_verified_until','created_at','updated_at','locked_until','date_of_birth'];
	
	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	protected $locked = ['first_name','middle_name','last_name','date_of_birth'];


    protected $casts = [
        'preferences' => 'array'
    ];

  
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' 	=> ['first_name','last_name'],
				'onUpdate'	=> true
            ]
        ];
    }

   /**
     * Default attribute values
     *
     * @var array
     */
    protected $attributes = [
        'status'    =>     'private'
    ];

    /**
     * The two statuses that a user can have
     * @var [type]
     */
	public static $statuses = [
        'private'    =>  'hidden',
        'public'     =>  'visible'
    ];

	/**************************************** Overrides **************************************** */

	public static function boot(){
		parent::boot();

		static::creating(function($model){
			event(new UserCreating($model));

			return true;
		});

		static::created(function($model){
			event(new UserCreated($model));

			return true;
		});

		static::updating(function($model){

			event(new UserUpdating($model));

			return true;
		});

		static::updated(function($model){

			event(new UserUpdated($model));

			$model->load('roles');

			$data = [

			'event' => 'UserWithId'.$model->id.'IsVerified',
			'data'  => [

					'permissions' => $model->permissions,
					'identity_verified' => $model->identity_verified

				]
			];

		 	Redis::publish('connection', json_encode($data));

			return true;
		});

		static::saving(function($model){

			if(!$model->api_token){
				$model->api_token = str_random(60);
			}
			return true;

		});

		static::deleted(function($model){
			event(new UserDeleted($model));
			return true;
		});
	}


    public function setVisibility(){

        //If self or show-other-private-user
        if(Auth::check() && (Auth::user()->id==$this->id || Auth::user()->hasRole('administrator'))){


            $this->setVisible(['email','id','slug','ethnic_origin_id','password','first_name','middle_name','last_name','date_of_birth','public','website', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted', 'community_id','identity_verified','address_verified_until','preferences','status']);
        }

        if($this->publiclyVisible){
			$this->setVisible(['first_name','last_name','id','community_id']);
        }


        return $this;
    }


	/**************************************** Custom Methods **************************************** */
    
	/**
	 * @param Adds the named role to a user
	 */
    public function addUserRoleByName($name){
	    $userRole = Role::where('name','=',$name)->firstOrFail();

	    if (!$this->roles->contains($userRole->id)) {
	    	$this->roles()->attach($userRole->id);
		}

	    // Default users are not assigned a role. Once any role is
	    // assigned (Admin, Representative, Citizen), it means their identity
	    // has been verified and you will update this field for 4 years in time.
    	$this->addressVerifiedUntil = Carbon::now()->addYears(4);
    }

    public function removeUserRoleByName($name){
	    $userRole = Role::where('name','=',$name)->firstOrFail();
	    $this->roles()->detach($userRole->id);
    }

    public function removeUserRole($id){
	    $userRole = Role::where('id','=',$id)->firstOrFail();
	    $this->roles()->detach($userRole->id);
    }

   

	/****************************************** Getters & Setters ************************************/

	

	/**
	 * @param string takes a string and hashes it into a password
	 */
	public function setPasswordAttribute($value){
		$this->attributes['password'] = Hash::make($value);
	}
	
	public function setAddressVerifiedUntilAttribute($input){
		if ($this->getAttributes('identity_verified') === 0){
			return false;
		}

		$this->attributes['address_verified_until'] = Carbon::now()->addYears(4);
	}

    public function getAddressVerifiedUntilAttribute($attr){
    	if(!$attr){
    		return null;
    	}

        $carbon = Carbon::parse($attr);

        return array(
            'diff'          =>      $carbon->diffForHumans(),
            'alpha_date'    =>      $carbon->format('j F Y'),
            'carbon'        =>      $carbon
        );
    }

	/**
	 * @return The permissions attached to this user through entrust
	 */
	public function getPermissionsAttribute(){
		$permissions = [];
		foreach ($this->roles as $role){
			$role_permissions = $role->perms()->get();
			foreach($role_permissions as $permission){
				if(!in_array($permission->name,$permissions)){
					$permissions[]=$permission->name;
				}
			}
		}
		return $permissions;
	}

	/**
	 * @return The permissions attached to this user through entrust
	 */
	public function getUserRoleAttribute(){
		$user_role = [];
		foreach ($this->roles as $role){
			$user_role[] = $role->display_name;
		}
		return $user_role;
	}

	/**
	 * @return The permissions attached to this user through entrust
	 */
	public function getAvatarAttribute(){
		if (!array_key_exists('avatar',$this->relations))
	    	$this->load('avatar');		
		return $this->getRelation('avatar');
	}


	public function getTotalDelegationsToAttribute(){
		// if relation is not loaded already, let's do it first
	  	if (!array_key_exists('totalDelegationsTo',$this->relations))
	    	$this->load('totalDelegationsTo');
		$related = $this->getRelation('totalDelegationsTo');
	 	
	  	// then return the count directly
	  	return ($related) ? $related->total : 0;
	}

	public function getTotalDelegationsFromAttribute(){
		// if relation is not loaded already, let's do it first
	  	if (!array_key_exists('totalDelegationsFrom',$this->relations))
	    	$this->load('totalDelegationsFrom');
		$related = $this->getRelation('totalDelegationsFrom');
	 	
	  	// then return the count directly
	  	return ($related) ? $related->total : 0;
	}


	public function getNeedIdentificationAttribute(){
		if($this->hasRole('citizen')){
			return false;
		}
		if(is_numeric($this->government_identification_id)){
			return false;
		}
		return true;
	}

	public function setPublicAttribute($value){
		if($this->hasRole('representative') && !$value){
			abort(403,'A representative must have a pubilc profile');
		}
		$this->attributes['public'] = $value; //This was setting everyone to public
	}





	/************************************* Casts & Accesors *****************************************/

	/**
	 * @return relation the sum of all the votes on this motion, negative means it's not passing, positive means it's passion
	 */

	public function totalDelegationsTo()
	{
	  return $this->hasOne('App\Delegation','delegate_to_id')
	    ->select('delegate_to_id', DB::raw('count(*) as total'));
	}


	/**
	 * @return relation the sum of all the votes on this motion, negative means it's not passing, positive means it's passion
	 */

	public function totalDelegationsFrom()
	{
	  return $this->hasOne('App\Delegation','delegate_from_id')
	    ->select('delegate_from_id', DB::raw('count(*) as total'));
	}


	/************************************* Scopes *****************************************/
   	
	/**
     * Checks the user is public
	 * @param query 
	 */    
   	public function scopeArePublic($query){
        return $query->where('public',1);
    }


    /**
     * Checks the user has the email
	 * @param query 
	 */    

    public function scopeWithEmail($query,$email){
    	return $query->where('email',$email);
    }


    /**
     * Makes sure the voter is a verified Canadian citizen who is living in Yellowknife
	 * @param query 
	 */

    public function scopeValidVoter($query){
		return $query->where('address_verified_until','>=',Carbon::now())
			->whereHas('roles',function($query){
				$query->where('name','citizen');
			});
    }

    public function scopeRepresentative($query){
		return $query->whereHas('roles',function($query){
				$query->where('name','representative');

			});
    }

    public function scopeNotRepresentative($query){
		return $query->whereDoesntHave('roles',function($q){
				$q->where('name','representative');

			});
    }

    public function scopeNotCitizen($query){
		return $query->whereDoesntHave('roles',function($q){
				$q->where('name','citizen');
			});
    }

    /**
     * Searches for particular attributes to narrow down scope
	 * @param query 
	 */

	public function scopeVerified($query){
		return $query->where('identity_verified',1);
	}

	public function scopeUnverified($query){
		return $query->where('identity_verified', 0);
	}

	public function scopeAddressUnverified($query){
		return $query->where('address_verified_until', null);
	}

	public function scopeAddressNotSet($query){
		return $query->whereNotNull('street_name'); 
	}

    public function scopeHasRoles($query,$roles){
       return $query->whereHas('roles',function($query) use ($roles){
             $query->whereIn('name',$roles);
      });
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

	public function deferredVotes(){
		return $this->hasMany('App\Vote','deferred_to_id');
	}

	public function delegatedTo(){
		return $this->hasMany('App\Delegation','delegate_to_id');
	}

	public function delegatedFrom(){
		return $this->hasMany('App\Delegation','delegate_from_id');
	}

	public function roles(){
	    return $this->belongsToMany(Role::class); //,'assigned_roles'
	}

	public function modificationTo(){
		return $this->hasMany('App\UserModification','modification_to_id');
	}

	public function modificationBy(){
		return $this->hasMany('App\UserModification','modification_by_id');
	}

	public function governmentIdentification(){
		return $this->belongsTo('App\File','government_identification_id');
	}

	public function avatar(){
		return $this->belongsTo('App\File','avatar_id');
	}

	public function community(){
		return $this->belongsTo('App\Community');
	}
}