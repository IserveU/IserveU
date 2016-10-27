<?php

namespace App;

use App\Events\Comment\CommentCreated;
use App\Events\Comment\CommentDeleted;
use App\Events\Comment\CommentUpdated;
use App\Repositories\Caching\CachedModel;
use App\Repositories\Contracts\VisibilityModel;
use App\Repositories\StatusTrait;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Comment extends NewApiModel implements CachedModel, VisibilityModel
{
    use StatusTrait;

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
    protected $visible = [''];


    protected $with = ['vote.user', 'commentRankRelation'];


    /**
     * The attributes appended and returned (if visible) to the user.
     *
     * @var array
     */
    protected $appends = ['motion', 'commentRank', 'user', 'motionTitle', 'motionId'];


    /**
     * The fields that are dates/times.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];


    protected $attributes = [
        'status'    => 'private',
    ];

    /**
     * The two statuses that a comment can have.
     *
     * @var array
     */
    public static $statuses = [
        'private'    => 'hidden',
        'public'     => 'visible',
    ];

    /**************************************** Standard Methods **************************************** */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            event(new CommentCreated($model));

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
        if (Auth::check() && (Auth::user()->id == $this->id || Auth::user()->can('show-comment'))) {
            $this->skipVisibility();
        }

        if ($this->publiclyVisible) {
            $this->addVisible(['vote.user', 'user', 'userName']);
        }


        $this->addVisible(['text', 'created_at', 'id', 'commentRank', 'motionTitle', 'motionId']);


        return $this;
    }

    /**************************************** Custom Methods **************************************** */




    /****************************************** Getters & Setters ************************************/

    /**
     * @return int the mutator to get the sum of all the comment votes
     */
    public function getCommentRankAttribute()
    {
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
     * @return The sum of all the comment votes
     */
    public function commentRankRelation()
    {
        return $this->hasOne('App\CommentVote')
        ->selectRaw('comment_id, sum(position) as rank')
        ->groupBy('comment_id');
    }

    /**
     * Gets the motion title for Comment Index requests.
     *
     * @return string The title of the motion this was made on
     */
    public function getMotionTitleAttribute()
    {
        return $this->motion->title;
    }

    /**
     * Gets the ID of the motion this was made on.
     *
     * @return int The ID of the motion this was made on
     */
    public function getMotionIdAttribute()
    {
        return $this->motion->id;
    }

    /************************************* Scopes *****************************************/

    public function scopePosition($query, $position)
    {
        return $query->whereHas('vote', function ($q) use ($position) {
            $q->where('position', $position);
        });
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query = $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query = $query->where('created_at', '<=', $endDate);
        }

        return $query;
    }

    public function scopeOrderBy($query, $field, $direction)
    {
        return $query->orderBy($field, $direction);
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

    /**********************************  Relationships *****************************************/
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
        $this->load(['vote.user' => function ($q) use (&$user) {
            $user = $q->first();
        }]);

        return $user;
    }

    /**
     * A bridge to the user of this comment.
     *
     * @return Collection A collection of comments
     */
    public function getMotionAttribute()
    {
        $this->load(['vote.motion' => function ($q) use (&$motion) {
            $motion = $q->first();
        }]);

        return $motion;
    }
}
