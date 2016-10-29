<?php

namespace App;

use App\Events\User\UserCreated;
use App\Events\User\UserCreating;
use App\Events\User\UserDeleted;
use App\Events\User\UserUpdated;
use App\Events\User\UserUpdating;
use App\Notifications\Authentication\RoleGranted;
use App\Repositories\Caching\CachedModel;
use App\Repositories\Contracts\VisibilityModel;
use App\Repositories\StatusTrait;
use Auth;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use DB;
use Event;
use Hash;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends NewApiModel implements AuthorizableContract, CanResetPasswordContract,Authenticatable, CachedModel, VisibilityModel
{
    use Authorizable, CanResetPassword, AuthenticatableTrait, Notifiable, StatusTrait, Sluggable, SluggableScopeHelpers; //, StatusTrait;

    use EntrustUserTrait{
        // EntrustUserTrait::can as may; //There is an entrust collision here
        // Authorizable::can insteadof EntrustUserTrait;

        Authorizable::can as may; //There is an entrust collision here
        EntrustUserTrait::can insteadof Authorizable;
    }

    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['email', 'ethnic_origin_id', 'password', 'first_name', 'middle_name', 'last_name', 'date_of_birth', 'public', 'website', 'postal_code', 'street_name', 'street_number', 'unit_number', 'agreement_accepted', 'community_id', 'identity_verified', 'address_verified_until', 'preferences', 'status', 'phone'];


    protected $visible = [''];
    protected $hidden = ['password'];


    /**
     * The attributes appended and returned (if visible) to the user.
     *
     * @var array
     */
    protected $appends = ['permissions', 'totalDelegationsTo', 'user_role', 'avatar', 'need_identification', 'agreement_accepted'];

    protected $with = ['roles', 'community'];


    /**
     * Fields that are unique so that the ID of this field can be appended to them in update validation.
     *
     * @var array
     */
    protected $unique = ['email', 'remember_token'];


    /**
     * The fields that are dates/times.
     *
     * @var array
     */
    protected $dates = ['address_verified_until', 'created_at', 'updated_at', 'locked_until', 'date_of_birth'];

    /**
     * The fields that are locked. When they are changed they cause events to be fired (like resetting people's accounts/votes).
     *
     * @var array
     */
    protected $locked = ['first_name', 'middle_name', 'last_name', 'date_of_birth'];


    protected $casts = [
        'preferences' => 'array',
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
                'source'      => ['first_name', 'last_name'],
                'onUpdate'    => true,
            ],
        ];
    }

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'status'        => 'private',
        'preferences'   => '',
    ];

    /**
     * The two statuses that a user can have.
     *
     * @var array
     */
    public static $statuses = [
        'private'    => 'hidden',
        'public'     => 'visible',
    ];

    /**************************************** Overrides **************************************** */

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            event(new UserCreating($model));

            return true;
        });

        static::created(function ($model) {
            event(new UserCreated($model));

            return true;
        });

        static::updating(function ($model) {
            event(new UserUpdating($model));

            return true;
        });

        static::updated(function ($model) {
            event(new UserUpdated($model));

            return true;
        });

        static::deleted(function ($model) {
            event(new UserDeleted($model));

            return true;
        });
    }

    //////////////////////// Caching Implementation

    /**
     * Remove this items cache and nested elements.
     *
     * @param Model $fromModel The model calling this (if exists)
     *
     * @return null
     */
    public function flushCache($fromModel = null)
    {
        \Cache::flush(); //Just for now
    }

    /**
     * Clears the caches of related models or there relations if needed.
     *
     * @param Model $fromModel The model calling this (if exists)
     *
     * @return null
     */
    public function flushRelatedCache($fromModel = null)
    {
        \Cache::flush(); //Just for now
    }

    //////////////////////// Visibility Implementation

    public function setVisibility()
    {

        //If self or show-other-private-user
        if (Auth::check() && (Auth::user()->id == $this->id || Auth::user()->hasRole('administrator'))) {
            $this->addVisible(['id', 'email', 'slug', 'first_name', 'middle_name', 'last_name', 'postal_code', 'street_name', 'street_number', 'unit_number', 'community_id', 'status', 'ethnic_origin_id', 'date_of_birth', 'address_verified_until', 'agreement_accepted', 'identity_verified', 'preferences', 'login_attempts', 'locked_until', 'agreement_accepted_date', 'deleted_at', 'remember_token', 'created_at', 'updated_at', 'government_identification_id', 'avatar_id', 'api_token', 'permissions', 'phone']);
        }


        if ($this->publiclyVisible) {
            $this->addVisible(['first_name', 'last_name', 'id', 'community_id']);
        }


        return $this;
    }

    /**************************************** Role Enhancement **************************************** */

    /**
     * @param Adds the named role to a user
     */
    public function addRole($role)
    {
        $role = $this->resolveRole($role);

        if ($role && !$this->roles->contains($role->id)) {
            $this->roles()->attach($role->id);
        }

        if ($this->getPreference('authentication.notify.user.onrolechange')) {
            $this->notify(new RoleGranted($role));
        }

        $this->generateApiToken()->save();
    }

    public function removeRole($role)
    {
        $role = $this->resolveRole($role);

        $this->roles()->detach($role->id);

        $this->generateApiToken()->save();
    }

    public function resolveRole($role)
    {
        if ($role instanceof Role) {
            return $role;
        }

        if (is_numeric($role)) {
            return Role::find($role);
        }

        return Role::where('name', $role)->first();
    }

    public function generateApiToken()
    {
        $this->api_token = str_random(99);

        return $this;
    }

    /****************************************** Getters & Setters ************************************/

    /**
     * @param string takes a string and hashes it into a password
     */
    public function setAgreementAcceptedAttribute($value)
    {
        if ($value) {
            $this->attributes['agreement_accepted_date'] = Carbon::now();
        }
    }

    /**
     * @param string takes a string and hashes it into a password
     */
    public function getAgreementAcceptedAttribute()
    {
        if (!array_key_exists('agreement_accepted_date', $this->attributes)) {
            return false;
        }

        if (!$this->attributes['agreement_accepted_date']) {
            return false;
        }

        return true;
    }

    /**
     * @param string takes a string and hashes it into a password
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setAddressVerifiedUntilAttribute($input)
    {
        if ($this->getAttributes('identity_verified') === 0) {
            return false;
        }

        $this->attributes['address_verified_until'] = Carbon::now()->addYears(4);
    }

    public function getAddressVerifiedUntilAttribute($attr)
    {
        if (!$attr) {
            return;
        }

        $carbon = Carbon::parse($attr);

        return [
            'diff'          => $carbon->diffForHumans(),
            'alpha_date'    => $carbon->format('j F Y'),
            'carbon'        => $carbon,
        ];
    }

    /**
     * @return The permissions attached to this user through entrust
     */
    public function getPermissionsAttribute()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $role_permissions = $role->perms()->get();
            foreach ($role_permissions as $permission) {
                if (!in_array($permission->name, $permissions)) {
                    array_push($permissions, $permission->name);
                }
            }
        }

        return $permissions;
    }

    /**
     * A bridge to the comment votes of this user.
     *
     * @return Collection A collection of comment votes
     */
    public function getCommentVotesAttribute()
    {
        $this->load(['votes.commentVotes' => function ($q) use (&$commentVotes) {
            $commentVotes = $q->get()->unique();
        }]);

        return $commentVotes;
    }

    /**
     * @return The permissions attached to this user through entrust
     */
    public function getUserRoleAttribute()
    {
        $user_role = [];
        foreach ($this->roles as $role) {
            $user_role[] = $role->display_name;
        }

        return $user_role;
    }

    /**
     * @return The permissions attached to this user through entrust
     */
    public function getAvatarAttribute()
    {
        if (!array_key_exists('avatar', $this->relations)) {
            $this->load('avatar');
        }

        return $this->getRelation('avatar');
    }

    public function getTotalDelegationsToAttribute()
    {
        // if relation is not loaded already, let's do it first
        if (!array_key_exists('totalDelegationsTo', $this->relations)) {
            $this->load('totalDelegationsTo');
        }
        $related = $this->getRelation('totalDelegationsTo');

        // then return the count directly
        return ($related) ? $related->total : 0;
    }

    public function getTotalDelegationsFromAttribute()
    {
        // if relation is not loaded already, let's do it first
        if (!array_key_exists('totalDelegationsFrom', $this->relations)) {
            $this->load('totalDelegationsFrom');
        }
        $related = $this->getRelation('totalDelegationsFrom');

        // then return the count directly
        return ($related) ? $related->total : 0;
    }

    public function getNeedIdentificationAttribute()
    {
        if ($this->hasRole('citizen')) {
            return false;
        }
        if (is_numeric($this->government_identification_id)) {
            return false;
        }

        return true;
    }

    public function setPublicAttribute($value)
    {
        if ($this->hasRole('representative') && !$value) {
            abort(403, 'A representative must have a pubilc profile');
        }
        $this->attributes['public'] = $value; //This was setting everyone to public
    }

    /**
     * Sets a preference in the preferences array.
     *
     * @param string         $key   Key in the dot notation
     * @param String/Integer $value The value to set the key to be
     * @param bool           $force If you wish to set a value
     */
    public function setPreference($key, $value, $force = false)
    {
        $preferences = $this->preferences;

        if (!$force && !array_has($preferences, $key)) {
            throw new \Exception('Preference key does not exist');
        }

        array_set($preferences, $key, $value);

        $this->preferences = $preferences;
    }

    /**
     * Gets a preference in the preferences array.
     *
     * @param string $key Key in the dot notation
     *
     * @return Value of the preference
     */
    public function getPreference($key)
    {
        $preferences = $this->preferences;

        if (!array_has($preferences, $key)) {
            throw new \Exception('Preference key does not exist');
        }

        return array_get($preferences, $key);
    }

    /************************************* Casts & Accesors *****************************************/

    /**
     * @return relation the sum of all the votes on this motion, negative means it's not passing, positive means it's passion
     */
    public function totalDelegationsTo()
    {
        return $this->hasOne('App\Delegation', 'delegate_to_id')
        ->select('delegate_to_id', DB::raw('count(*) as total'));
    }

    /**
     * @return relation the sum of all the votes on this motion, negative means it's not passing, positive means it's passion
     */
    public function totalDelegationsFrom()
    {
        return $this->hasOne('App\Delegation', 'delegate_from_id')
        ->select('delegate_from_id', DB::raw('count(*) as total'));
    }

    /************************************* Scopes *****************************************/

    /**
     * Checks the user is public.
     *
     * @param query
     */
    public function scopeArePublic($query)
    {
        return $query->where('public', 1);
    }

    /**
     * Checks the user has the email.
     *
     * @param query
     */
    public function scopeWithEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Makes sure the voter is a verified Canadian citizen who is living in Yellowknife.
     *
     * @param query
     */
    public function scopeValidVoter($query)
    {
        return $query->where('address_verified_until', '>=', Carbon::now())
            ->whereHas('roles', function ($query) {
                $query->where('name', 'citizen');
            });
    }

    public function scopeRepresentative($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', 'representative');
        });
    }

    public function scopeNotRepresentative($query)
    {
        return $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'representative');
        });
    }

    public function scopeNotCitizen($query)
    {
        return $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'citizen');
        });
    }

    /**
     * Searches for particular attributes to narrow down scope.
     *
     * @param query
     */
    public function scopeVerified($query)
    {
        return $query->where('identity_verified', 1);
    }

    public function scopeUnverified($query)
    {
        return $query->where('identity_verified', 0);
    }

    public function scopeAddressUnverified($query)
    {
        return $query->where('address_verified_until', null);
    }

    public function scopeAddressNotSet($query)
    {
        return $query->whereNotNull('street_name');
    }

    public function scopeHasRoles($query, $roles)
    {
        return $query->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        });
    }

    public function scopePreference($query, $key, $value)
    {
        $key = str_replace('.', '->', $key);

        return $query->where('preferences->'.$key, $value);
    }

    /**********************************  Relationships *****************************************/

    public function ethnicOrigin()
    {
        return $this->belongsTo('App\EthnicOrigin');
    }

    public function motions()
    {
        return $this->hasMany('App\Motion');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function comments()
    {
        return $this->hasManyThrough('App\Comment', 'App\Vote');
    }

    public function deferredVotes()
    {
        return $this->hasMany('App\Vote', 'deferred_to_id');
    }

    public function delegatedTo()
    {
        return $this->hasMany('App\Delegation', 'delegate_to_id');
    }

    public function delegatedFrom()
    {
        return $this->hasMany('App\Delegation', 'delegate_from_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class); //,'assigned_roles'
    }

    public function modificationTo()
    {
        return $this->hasMany('App\UserModification', 'modification_to_id');
    }

    public function modificationBy()
    {
        return $this->hasMany('App\UserModification', 'modification_by_id');
    }

    public function governmentIdentification()
    {
        return $this->belongsTo('App\File', 'government_identification_id');
    }

    public function avatar()
    {
        return $this->belongsTo('App\File', 'avatar_id');
    }

    public function community()
    {
        return $this->belongsTo('App\Community');
    }
}
