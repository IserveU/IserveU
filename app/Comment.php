<?php

namespace App;

use App\Events\Comment\CommentCreated;
use App\Events\Comment\CommentDeleted;
use App\Events\Comment\CommentUpdated;
use App\Filters\CommentFilter;
use App\Repositories\Caching\Cacheable;
use App\Repositories\Caching\CachedModel;
use App\Repositories\Contracts\VisibilityModel;
use App\Repositories\StatusTrait;
use Auth;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Comment extends NewApiModel implements CachedModel, VisibilityModel
{
    use StatusTrait, Cacheable;

    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['text', 'vote_id', 'status'];

    /**
     * The default attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $visible = [];

    protected $with = ['vote.user.roles', 'vote.motion.user.community']; // 'commentRankRelation' not loaded because orderByCommentRank loads an attribute

    /**
     * The attributes appended and returned (if visible) to the user.
     *
     * @var array
     */
    protected $appends = ['motion', 'commentWriter', 'commentRank', 'motionSlug', 'motionTitle'];

    /**
     * The fields that are dates/times.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    protected $attributes = [
        'status' => 'private',
    ];

    /**
     * The two statuses that a comment can have.
     *
     * @var array
     */
    public static $statuses = [
        'private' => 'hidden',
        'public'  => 'visible',
    ];

    /**************************************** Standard Methods **************************************** */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            event(new CommentCreated($model));
            //    $model->flushCache();

            return true;
        });

        static::updating(function ($model) {
            event(new CommentUpdated($model));

            return true;
        });

        static::deleting(function ($model) {
            if ($model->commentVotes) {
                //Database is doing this
            }

            return true;
        });

        static::deleted(function ($model) {
            event(new CommentDeleted($model));

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
        Cache::tags(['comment.filters'])->flush(); //All things tagged with comment.filters
        Cache::tags(['comment', 'comment.model'])->forget($this->id); //This records model data
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
        if ($this->motion) {
            Cache::tags(['motion', 'comment'])->forget($this->motion->id); //This records model data
        }
    }

    //////////////////////// Visibility Implementation

    public function setVisibility()
    {

        //If self or show-other-private-user
        if (Auth::check() && (Auth::user()->id == $this->id || Auth::user()->can('show-comment'))) {
            $this->skipVisibility();
        }

        if ($this->publiclyVisible) {
            $this->addVisible([]);
        }

        $this->addVisible(['text', 'created_at', 'id', 'commentRank', 'motionTitle', 'motionSlug', 'commentWriter']);

        return $this;
    }

    /**************************************** Custom Methods **************************************** */

    /****************************************** Getters & Setters ************************************/

    /**
     * An accessor that will efficiently figure out if the comment rank is required.
     *
     * @return int the sum of the comment_votes position
     */
    public function getCommentRankAttribute()
    {
        if (array_key_exists('commentRank', $this->attributes)) {
            return $this->attributes['commentRank'];
        }

        // if relation is not loaded already, let's do it first
        if (!array_key_exists('commentRankRelation', $this->relations)) {
            $this->load('commentRankRelation');
        }

        $related = $this->getRelation('commentRankRelation');

        // then return the count directly
        return ($related) ? (int) $related->rank : 0;
    }

    /************************************* Casts & Accesors *****************************************/

    /**
     * Gets the motion title for Comment Index requests.
     *
     * @return string The title of the motion this was made on
     */
    public function getMotionTitleAttribute()
    {
        if ($this->motion) {
            return $this->motion->title;
        }

        return 'Deleted Motion';
    }

    /**
     * Gets the ID of the motion this was made on.
     *
     * @return int The ID of the motion this was made on
     */
    public function getMotionSlugAttribute()
    {
        if ($this->motion) {
            return $this->motion->slug;
        }
    }

    public function getCommentWriterAttribute()
    {
        $data['community'] = $this->user->community ?? ['name' => 'Unknown Community', 'adjective' => 'Unknown Community'];
        $data['status'] = $this->user->status; // A little easier to spot issues

        if ($this->publiclyVisible) {
            $data['first_name'] = $this->user->first_name;
            $data['last_name'] = $this->user->last_name;

            //Can we also visit the user's profile
            if ($this->user->publiclyVisible) {
                $data['slug'] = $this->user->slug;
            }
        }

        return $data;
    }

    /************************************* Scopes *****************************************/

    /**
     * Executes the filters passed in on the comment.
     *
     * @param $query Builder Instance of the query builder
     * @param $filters MotionFilter an instance of the filter class for the query
     *
     * @return Builder
     */
    public function scopeFilter($query, CommentFilter $filters)
    {
        return $filters->apply($query);
    }

    public function scopePosition($query, $position)
    {
        return $query->whereHas('vote', function ($q) use ($position) {
            $q->where('position', $position);
        });
    }

    public function scopeBetweenDates($query, Carbon $startDate, Carbon $endDate)
    {
        if ($startDate) {
            $query = $query->whereDate('comments.created_at', '>', $startDate);
        }
        if ($endDate) {
            $query = $query->whereDate('comments.created_at', '<', $endDate);
        }

        return $query;
    }

    public function scopeOnMotion($query, $motionId)
    {
        return $query->whereHas('vote', function ($q) use ($motionId) {
            $q->where('motion_id', $motionId);
        });
    }

    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('vote', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Orders a comment by the sum of the votes that it has in the comment_votes table.
     * Comment votes can have a position of 1, 0 and -1 so the sum of all these entries works as a rank.
     *
     * @param Builder $query Query builder instance
     * @param string  $order asc/desc
     *
     * @return Builder instance
     */
    public function scopeOrderByCommentRank($query, $order = 'desc')
    {
        return $query->leftJoin('comment_votes', 'comment_votes.comment_id', '=', 'comments.id')
            ->groupBy('comments.id')
            ->addSelect(['comments.*', \DB::raw('sum(position) as commentRank')])
            ->orderBy('commentRank', $order);
    }

    /**********************************  Relationships *****************************************/

    /**
     * An alternative relationship with comment votes for DB count queries.
     */
    public function commentRankRelation()
    {
        return $this->hasOne('App\CommentVote')
        ->selectRaw('comment_id, sum(position) as rank')
        ->groupBy('comment_id');
    }

    public function commentVotes()
    {
        return $this->hasMany('App\CommentVote');
    }

    public function vote()
    {    //The user who cast a vote that then was allowed to comment on that side
        return $this->belongsTo('App\Vote');
    }

    /**
     * A bridge to the user of this comment.
     *
     * @return Collection A collection of comments
     */
    public function getUserAttribute()
    {
        //If this is failing it needs to be eager loaded. For some reason nested eager loading is not working
        return $this->vote->user;
    }

    /**
     * A bridge to the user of this comment.
     *
     * @return Collection A collection of comments
     */
    public function getMotionAttribute()
    {
        //Should be eager loaded, this will load it if it is not
        return $this->vote->motion;
    }
}
