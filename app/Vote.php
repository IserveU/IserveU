<?php

namespace App;

use App\Events\Vote\VoteUpdated;
use App\Filters\VoteFilter;
use App\Repositories\Caching\CachedModel;
use Auth;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vote extends NewApiModel implements CachedModel
{
    use SoftDeletes;

    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'votes';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['motion_id', 'position', 'user_id'];

    /**
     * The default attributes eager loaded.
     *
     * @var array
     */
    protected $with = ['motion'];

    /**
     * The default attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $visible = [];

    /**
     * The attributes appended and returned (if visible) to the user.
     *
     * @var array
     */
    protected $appends = ['_motion_title'];

    /**************************************** Standard Methods *****************************************/
    public static function boot()
    {
        parent::boot();
        /* validation required on new */
        static::creating(function ($model) {
            return true;
        });

        static::created(function ($model) {
            return true;
        });

        static::updating(function ($model) {
            return true;
        });

        static::updated(function ($model) {
            // CheckCommentVotes
            event(new VoteUpdated($model));
            $model->flushRelatedCache();
            $model->flushCache();

            return true;
        });

        static::deleting(function ($model) {
            if ($model->comment) {
                $model->comment->delete();
            }

            return true;
        });
    }

    //////////////////////// Caching Implementation

    /**
     * Remove this items cache and nested elements.
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
     * @return null
     */
    public function flushRelatedCache($fromModel = null)
    {
        $this->motion->flushCache();
    }

    public function setVisibility()
    {
        if ($this->user->publiclyVisible) {
            $this->addVisible(['id', 'position', 'motion_id', 'id', 'deferred_to_id', '_motion_title']);
        }

        //If self or show-other-private-user
        if (Auth::check() && Auth::user()->id == $this->user_id) {
            $this->addVisible(['id', 'position', 'motion_id', 'user_id', 'deferred_to_id', 'visited', 'updated_at', '_motion_title']);
        }

        return $this;
    }

    /************************************* Custom Methods *******************************************/

    /************************************* Getters & Setters ****************************************/

    public function setPositionAttribute($value)
    {
        if (Auth::check() && Auth::user()->id == $this->user_id) {
            $this->attributes['deferred_to_id'] = null;
        }
        $this->attributes['position'] = $value;
    }

    public function getPositionHumanReadableAttribute()
    {
        if ($this->position == 1) {
            return 'agree';
        }
        if ($this->position == -1) {
            return 'disagree';
        }

        return 'abstain';
    }

    public function getMotionTitleAttribute()
    {
        if ($this->motion) {
            return $this->motion->title;
        }

        return 'Abandoned Motion';
    }

    /************************************* Casts & Accesors *****************************************/

    /************************************* Scopes ***************************************************/

    public function scopeFilter($query, VoteFilter $filters)
    {
        return $filters->apply($query);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deferred_to_id');
    }

    public function scopePassive($query)
    {
        return $query->whereNotNull('deferred_to_id');
    }

    public function scopeAgree($query)
    {
        return $query->where('position', 1);
    }

    public function scopeDisagree($query)
    {
        return $query->where('position', -1);
    }

    public function scopeAbstain($query)
    {
        return $query->where('position', 0);
    }

    public function scopeCast($query)
    {
        return $query->whereNotNull('position');
    }

    public function scopeByUser($query, $user)
    {
        if (is_int($user)) {
            return $query->where('user_id', $user);
        }

        return $query->where('user_id', $user->id);
    }

    /**
     * Motions that have a.
     *
     * @param Builder $query
     * @param array   $status An array of the statuses
     *
     * @return Builder
     */
    public function scopeMotionStatus($query, array $status)
    {
        return $query->whereHas('motion', function ($query) use ($status) {
            $query->whereIn('status', ['published']);
        });
    }

    public function scopeOnMotion($query, $motionIdent)
    {
        if (is_numeric($motionIdent)) {
            return $query->where('motion_id', $motionIdent);
        }

        return $query->whereHas('motion', function ($query) use ($motionIdent) {
            $query->where('slug', $motionIdent);
        });
    }

    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /************************************* Relationships ********************************************/

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function motion()
    {
        return $this->belongsTo('App\Motion');
    }

    public function comment()
    {
        return $this->hasOne('App\Comment');
    }

    public function commentVotes()
    {
        return $this->hasMany('App\CommentVote');
    }

    public function deferred()
    {
        return $this->belongsTo('App\User', 'deferred_to_id');
    }
}
