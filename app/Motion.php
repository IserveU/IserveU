<?php

namespace App;

use App\Events\Motion\MotionCreated;
use App\Events\Motion\MotionDeleted;
use App\Events\Motion\MotionSaving;
use App\Events\Motion\MotionUpdated;
use App\Filters\MotionFilter;
use App\Repositories\Caching\Cacheable;
use App\Repositories\Caching\CachedModel;
use App\Repositories\Contracts\VisibilityModel;
use App\Repositories\StatusTrait;
use Auth;
use Cache;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motion extends NewApiModel implements CachedModel, VisibilityModel
{
    use Sluggable, SluggableScopeHelpers, StatusTrait, SoftDeletes, Cacheable;

    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'motions';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['title', 'text', 'summary', 'department_id', 'closing_at', 'status', 'user_id', 'implementation'];

    /**
     * The attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $visible = [''];

    /**
     * The attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $hidden = ['content'];

    /**
     * The attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $with = ['department', 'user', 'files'];

    /**
     * The attributes appended and returned (if visible) to the user.
     *
     * @var array
     */
    protected $appends = ['_motionOpenForVoting', '_userVote', '_userComment', '_rank', 'text'];

    /**
     * The fields that are dates/times.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'closing_at', 'published_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source'    => ['title'],
                 'onUpdate' => true,
            ],
        ];
    }

    protected $attributes = [
        'status'  => 'draft',
        'content' => '{"text": ""}',
    ];

    /**
     * Casts fields to database columns.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * The  statuses that a motion can have.
     *
     * @var array
     */
    public static $statuses = [
        'draft'     => 'hidden',
        'review'    => 'hidden',
        'published' => 'visible',
        'closed'    => 'visible',
    ];

    /**************************************** Standard Methods **************************************** */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->user_id) {
                $model->user_id = Auth::user()->id;
            }

            return true;
        });

        static::saving(function ($model) {
            // Does  Nothing
            event(new MotionSaving($model));

            return true;
        });

        static::created(function ($model) {
            // Does  Nothing
            event(new MotionCreated($model));
            $model->flushCache();

            return true;
        });

        static::updating(function ($model) {
            // SendNotificationEmail
            // AlertVoters
            event(new MotionUpdated($model));
            $model->flushCache();

            return true;
        });

        static::deleted(function ($model) {
            event(new MotionDeleted($model));
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
        Cache::tags(['motion.filters'])->flush();
        Cache::tags(['motion', 'motion.model'])->forget($this->slug);
        Cache::tags(['motion', 'motion.model'])->forget($this->id);
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
        Cache::tags(['motion', 'comment'])->forget($this->id); // MotionComment Index
    }

    //////////////////////// Visibility Implementation

    public function setVisibility()
    {

        //If self or show-other-private-user
        if (Auth::check() && (Auth::user()->id == $this->user_id || Auth::user()->can('show-motion'))) {
            $this->addVisible(['id', 'user_id', 'title', 'summary', 'slug', 'text', 'department', 'closing_at', 'published_at', 'status', 'created_at', 'updated_at', 'user', '_rank', '_motionOpenForVoting', 'implementation']);
        }

        if (Auth::check()) {
            $this->addVisible(['_userVote', '_userComment']);
        }

        if ($this->publiclyVisible) {
            $this->addVisible(['id', 'user_id', 'title', 'summary', 'slug', 'text', 'department', 'closing_at', 'published_at', 'status', 'created_at', 'updated_at', 'user', '_motionOpenForVoting', '_rank', 'implementation']);
        }

        return $this;
    }

    /************************************* Getters & Setters ****************************************/

    /**
     * Converts the date/time to a handy set of date details.
     *
     * @param string $attr String of date to be parsed
     *
     * @return array
     */
    public function getClosingAtAttribute($attr)
    {
        return formatIntoReadableDate($attr);
    }

    /**
     * Converts the date/time to a handy set of date details.
     *
     * @param string $attr String of date to be parsed
     *
     * @return array
     */
    public function getPublishedAtAttribute($attr)
    {
        return formatIntoReadableDate($attr);
    }

    public function setClosingAtAttribute($value)
    {
        $this->attributes['closing_at'] = $value;

        return true;
    }

    /**
     * Refreshes the eager loaded relationship.
     *
     * @param [type] $value [description]
     */
    public function setDepartmentIdAttribute($value)
    {
        $this->attributes['department_id'] = $value;

        if ($this->exists()) {
            $this->load('department');
        }
    }

    public function getMotionOpenForVotingAttribute()
    {
        //Created without a status but not saved
        if (!array_key_exists('status', $this->attributes)) {
            return false;
        }

        //This motion is not published and cannot be voted on
        if ($this->attributes['status'] != 'published') {
            return false;
        }

        // Motions can stay open forever if their closing is null
        if ($this->closing_at == null || $this->closing_at['carbon'] == null) {
            return true;
        }

        if ($this->closing_at['carbon']->lt(Carbon::now())) {
            $this->attributes['status'] = 'closed';
            $this->save();

            return false;
        }

        return true;
    }

    public function getUserVoteAttribute()
    {
        if ($this->thisUserVote) {
            return $this->thisUserVote->toArray();
        }
    }

    public function getUserCommentAttribute()
    {
        if ($this->thisUserVote) {
            return $this->thisUserVote->comment;
        }
    }

    /**
     * A bridge to the comments on this motion.
     *
     * @return Collection A collection of comments
     */
    public function getCommentsAttribute()
    {
        $this->load(['votes.comment' => function ($q) use (&$comments) {
            $comments = $q->get()->unique();
        }]);

        return $comments;
    }

    /**
     * @return int the mutator to get the sum of all the votes
     */
    public function getRankAttribute()
    {
        // if relation is not loaded already, let's do it first
        if (!array_key_exists('rankRelation', $this->relations)) {
            $this->load('rankRelation');
        }

        $related = $this->getRelation('rankRelation');

        // then return the count directly
        return ($related) ? (int) $related->rank : 0;
    }

    /**
     * @return text string for the passing
     */
    public function getPassingStatusAttribute()
    {
        if ($this->rank > 0) {
            return 'agree';
        }
        if ($this->rank < 0) {
            return 'disagree';
        }

        return 'tie';
    }

    /**
     * Sets the JSON field.
     *
     * @param string $input content of the text field
     */
    public function setTextAttribute($input)
    {
        if (!$this->content) {
            $this->content = [];
        }
        $this->content = array_merge($this->content, ['text' => $input]);
    }

    /**
     * Sets the JSON field.
     *
     * @param string $input content of the text field
     */
    public function getTextAttribute()
    {
        return $this->content['text'];
    }

    /************************************* Scopes ***************************************************/

    /**
     * Executes the filters passed in on the.
     *
     * @param $query Builder Instance of the query builder
     * @param $filters MotionFilter Motion filter class
     *
     * @return Builder
     */
    public function scopeFilter($query, MotionFilter $filters)
    {
        return $filters->apply($query);
    }

    /* check if motion has more votes than the query */
    public function scopeRankGreaterThan($query, $rank = 0)
    {
        return $query->whereHas('votes', function ($query) use ($rank) {
            $query->havingRaw('SUM(position) > '.$rank);
        });
    }

    /* check if motion has lesss votes than the query */
    public function scopeRankLessThan($query, $rank = 0)
    {
        return $query->whereHas('votes', function ($query) use ($rank) {
            $query->havingRaw('SUM(position) < '.$rank);
        });
    }

    /**
     * Orders a motion by the sum of the votes that it has in votes table.
     * Votes can have a position of 1, 0 and -1 so the sum of all these entries works as a rank.
     *
     * @param Builder $query Query builder instance
     * @param string  $order asc/desc
     *
     * @return Builder instance
     */
    public function scopeOrderByRank($query, $order = 'desc')
    {
        return $query->rightJoin('votes', 'votes.motion_id', '=', 'motions.id')
            ->groupBy('motions.id')
            ->addSelect(['motions.*', \DB::raw('sum(position) as rank')])
            ->orderBy('rank', $order);
    }

    public function scopeWriter($query, $user)
    {
        if (is_numeric($user)) {
            return $query->where('user_id', $user);
        }

        return $query->where('user_id', $user->id);
    }

    //if these functions below are not used for any other purpose, they
    // are deprecated due to filter 'orderBy'do the job already.
    public function scopePublishedAfter($query, Carbon $time)
    {
        return $query->where('published_at', '>=', $time);
    }

    public function scopePublishedBefore($query, Carbon $time)
    {
        return $query->where('published_at', '<=', $time);
    }

    public function scopeClosingBefore($query, Carbon $time)
    {
        return $query->where('closing_at', '<=', $time);
    }

    public function scopeClosingAfter($query, Carbon $time)
    {
        return $query->where('closing_at', '>=', $time);
    }

    /************************************* Relationships ********************************************/

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    /**
     * An alternative relationship with comment votes for DB count queries.
     */
    public function rankRelation()
    {
        return $this->hasOne('App\Vote')
        ->selectRaw('motion_id, sum(position) as rank')
        ->groupBy('motion_id');
    }

    public function thisUserVote()
    {
        if (Auth::check()) {
            return $this->hasOne('App\Vote')->where('user_id', Auth::user()->id);
        }

        return $this->hasOne('App\Vote');
    }
}
