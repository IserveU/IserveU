<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Role;
use Auth;
use Hash;
use Request;
use Carbon\Carbon;
use Redis;

use App\Events\UserUpdated;
use App\Events\UserUpdating;
use App\Events\UserCreated;
use App\Events\UserDeleted;

use Event;
use Mail;
use DB;


class User extends ApiModel implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, SoftDeletes, EntrustUserTrait, Eloquence, Mappable;

	/**
	 * The name of the table for this model, also for the permissions set for this model
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are fillable by a creator of the model
	 * @var array
	 */
	protected $fillable = ['email','ethnic_origin_id','public','password','first_name','middle_name','last_name','date_of_birth','public','website', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted'];

	/**
	 * The attributes fillable by the administrator of this model
	 * @var array
	 */
	protected $adminFillable = ['identity_verified', 'address_verified_until'];

	/**
	 * The default attributes included in any JSON/Array
	 * @var array
	 */
	protected $visible = ['public', 'user_role'];

	/**
	 * The attributes visible to an administrator of this model
	 * @var array
	 */
	protected $adminVisible = ['first_name','last_name','middle_name','email','ethnic_origin_id','date_of_birth','public','id','login_attempts','created_at','updated_at','identity_verified', 'permissions', 'user_role', 'votes','address_verified_until','government_identification','need_identification','avatar', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted'];
	/**
	 * The attributes visible to the user that created this model
	 * @var array
	 */
	protected $creatorVisible = ['first_name','last_name','middle_name','email','ethnic_origin_id','date_of_birth','public','id','permissions','votes','address_verified_until','need_identification','avatar', 'postal_code', 'street_name', 'street_number', 'unit_number','agreement_accepted'];

	/**
	 * The attributes visible if the entry is marked as public
	 * @var array
	 */
	protected $publicVisible =  ['first_name','last_name','public','id','votes','totalDelegationsTo','avatar'];

	/**
	 * The mapped attributes for 1:1 relations
	 * @var array
	 */
   	protected $maps = [
       	'government_identification'		=> 	'governmentIdentification'
    ];

	/**
	 * The attributes appended and returned (if visible) to the user
	 * @var array
	 */	
    protected $appends = ['permissions','totalDelegationsTo', 'user_role','avatar','government_identification','need_identification'];

    /**
     * The rules for all the variables
     * @var array
     */
	protected $rules = [	
		'email' 					=>	'email|unique:users,email',
	    'password'					=>	'min:8',
	    'first_name'				=>	'string',
	    'middle_name'				=>	'string',
	    'last_name'					=>	'string',
	    'ethnic_origin_id'			=>	'integer|exists:ethnic_origins,id',
	    'date_of_birth'				=>	'date|sometimes',
	    'public'					=>	'boolean',
        'id'       					=>	'integer',
	    'login_attempts'			=>	'integer',
	    'identity_verified'			=>	'boolean',
	    'remember_token'			=>	'unique:users,remember_token',
		'postal_code'				=>  'string',
		'street_name'				=>  'string',
		'street_number'				=>  'integer',
		'unit_number'				=>  'integer',
		'address_verified_until'	=>	'date|before:+1100 days',
		'agreement_accepted'		=>	'boolean'
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
		'email' 					=>	['tag'=>'input','type'=>'email','label'=>'EMAIL_ADDRESS','placeholder'=>'Email Address'],
	    'password'					=>	['tag'=>'input','type'=>'password','label'=>'PASSWORD','placeholder'=>'Your Password'],
	    'first_name'				=>	['tag'=>'input','type'=>'input','label'=>'FIRST_NAME','placeholder'=>'First Name'],
	    'middle_name'				=>	['tag'=>'input','type'=>'input','label'=>'MIDDLE_NAME','placeholder'=>'Middle Name'],
	    'last_name'					=>	['tag'=>'input','type'=>'input','label'=>'LAST_NAME','placeholder'=>'Last Name'],
	    'ethnic_origin_id'			=>	['tag'=>'md-select','type'=>'select','label'=>'ETHNIC_ORIGIN','placeholder'=>'Primary Ethnic Origin'],
	    'date_of_birth'				=>	['tag'=>'input','type'=>'date','label'=>'BIRTHDAY','placeholder'=>'Date of Birth'],
	    'public'					=>	['tag'=>'md-switch','type'=>'md-switch','label'=>'PUBLIC','placeholder'=>'Enable Public Profile'],
	    'identity_verified'			=>	['tag'=>'md-switch','type'=>'md-switch','label'=>'IDENTITY_VERIFIED','placeholder'=>'User Is Verified'],

	];


	/**
	 * The fields that are dates/times
	 * @var array
	 */
	protected $dates = ['address_verified_until','created_at','updated_at','locked_until'];
	
	/**
	 * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes)
	 * @var array
	 */
	protected $locked = ['first_name','middle_name','last_name','date_of_birth'];


	/**************************************** Standard Methods **************************************** */

	public static function boot(){
		parent::boot();

		/* validation required on new */		
		static::creating(function($model){

			if(!$model->validate()) return false;

			return true;
		});

		static::created(function($model){
			$user = User::find($model->id);
			event(new UserCreated($user));
			return true;
		});

		static::updating(function($model){
			if(!$model->validate()) return false;
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

		static::deleted(function($model){
			event(new UserDeleted($model));
			return true;
		});
	}


	/**************************************** Custom Methods **************************************** */
    
	/**
	 * @param Adds the named role to a user
	 */
    public function addUserRoleByName($name){
	    $userRole = Role::where('name','=',$name)->firstOrFail();
	    $this->roles()->attach($userRole->id);

	    // Default users are not assigned a role. Once any role is
	    // assigned (Admin, Councillor, Citizen), it means their identity
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

    public function getFillableAttribute(){
        if(!Auth::check()){ //If not logged in, don't go to parent
			return $this->fillable;
        }
        return parent::getFillableAttribute();
    }

    public function createDefaultDelegations($departments = null,  $councillors = null){
    	if(!$this->can('create-votes')){
    		return true;
    	}

    	if(!$departments){
			$departments =  Department::all();    		
    	}
    	if(!$councillors){
    		$councillors = 	User::councillor()->get();
    	}

    	if($councillors->isEmpty()){
            return true;// "there are no councillors";
        }

        if($this->hasRole('councillor')){
        	return true; //A councillor cannot delegate
        }

        // Code to potentially do this more efficiently with fewer database calls
        // $userDelegations = $user->delegatedFrom;
        // $filteredDepartments = $departments->filter(function($item){
        //     return $item->id; + is not in a flattened array of this users delegations
        // });
        //  $insertsArray = [];
        //foreach($filteredDepartments as $filteredDepartment){
            // Add to the inserts array, at the end do one huge insert
        //}
    	// $this->insert(Insert all these array items)
 		foreach($departments as $department){
            $leastDelegatedToCouncillor = $councillors->sortBy('totalDelegationsTo')->first();
            $newDelegation = new Delegation;
            $newDelegation->department_id       =   $department->id;
            $newDelegation->delegate_from_id    =   $this->id;
            $newDelegation->delegate_to_id      =   $leastDelegatedToCouncillor->id;
            $newDelegation->save();
        }
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

	/**
	 * @return The permissions attached to this user through entrust
	 */
	// public function getGovernmentIdentificationAttribute(){
	// 	if(!Auth::user()->can('administrate-users')){
	// 		return null;
	// 	}

	// 	if (!array_key_exists('governmentIdentification',$this->relations))
	//     	$this->load('governmentIdentification');		
	// 	return $this->getRelation('governmentIdentification');
	// }


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
		if($this->hasRole('councillor') && !$value){
			abort(403,'A councillor must have a pubilc profile');
		}
		$this->attributes['public'] = 1;
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

    public function scopeCouncillor($query){
		return $query->whereHas('roles',function($query){
				$query->where('name','councillor');

			});
    }

    public function scopeNotCouncillor($query){
		return $query->whereDoesntHave('roles',function($q){
				$q->where('name','councillor');

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
	    return $this->belongsToMany('App\Role'); //,'assigned_roles'
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
}