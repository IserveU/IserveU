<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OneTimeToken extends NewApiModel
{
    /**
     * The name of the table for this model, also for the permissions set for this model.
     *
     * @var string
     */
    protected $table = 'one_time_tokens';

    /**
     * The attributes that are fillable by a creator of the model.
     *
     * @var array
     */
    protected $fillable = ['token', 'user_id'];

    /**
     * There is no reason to ever show these.
     *
     * @var array
     */
    protected $visible = [''];

    /**
     * The attributes included in the JSON/Array.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * The attributes appended and returned (if visible) to the user.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The fields that are dates/times.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * @var string
     */
    public $updated_at = '';

    /**************************************** Standard Methods **************************************** */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->token = str_random(128);

            return true;
        });
    }

    public static function generateFor(User $user, $number = null)
    {
        return self::create([
          'user_id' => $user->id,
        ]);
    }

    /************************************* Scopes ***************************************************/

    public function scopeFor($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /************************************* Relationships ********************************************/

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
