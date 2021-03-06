<?php

namespace App\Repositories;

use Carbon\Carbon;

/**
 *   A reusable status and published trait to manage visibility of montions and users.
 **/
trait StatusTrait
{
    /**
     * Gets the statuses considered visible by this model to the general public.
     *
     * @return [type] [description]
     */
    public static function visibleStatuses()
    {
        return array_keys(array_filter(static::$statuses, function ($value, $key) {
            return $value == 'visible';
        }, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Gets the statuses considered visible by this model to the general public.
     *
     * @return [type] [description]
     */
    public static function hiddenStatuses()
    {
        return array_keys(array_filter(static::$statuses, function ($value, $key) {
            return $value == 'hidden';
        }, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Can be run by the model to check the status as valid.
     *
     * @return bool true
     */
    public function validStatus()
    {
        $validator = \Validator::make($this->attributes, [
            'status' => 'valid_status',
        ]);
        //This hasn't been catching in handler in the tests at least. WTF?
        if ($validator->fails()) {
            throw new \Illuminate\Foundation\Validation\ValidationException($validator);
        }

        return true;
    }

    public function scopeStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }

        return $query->where('status', $status);
    }

    /**
     * Get the public visibility of this model.
     *
     * @return bool If the model is considered to be publically visible
     */
    public function getPubliclyVisibleAttribute()
    {
        if (!$this->status) {
            abort(500, 'Default status not set in model');
        }

        if (static::$statuses[$this->status] == 'visible') {
            return true;
        }

        return false;
    }

    public function scopePubliclyVisible($query)
    {
        $query->whereIn('status', static::visibleStatuses());
    }

    public function scopePubliclyHidden($query)
    {
        $query->whereIn('status', static::hiddenStatuses());
    }

    public function scopePublishedBefore($query, $time)
    {
        return $query->whereDate('published_at', '<', $time);
    }

    public function scopePublishedAfter($query, $time)
    {
        return $query->whereDate('published_at', '>', $time);
    }

    public function scopeUpdatedBefore($query, $time)
    {
        return $query->where('updated_at', '<', $time);
    }

    public function scopeUpdatedAfter($query, $time)
    {
        return $query->where('updated_at', '>', $time);
    }

    public function scopeCreatedBefore($query, $time)
    {
        return $query->where('created_at', '<', $time);
    }

    public function scopeCreatedAfter($query, $time)
    {
        return $query->where('created_at', '>', $time);
    }

    public function scopeClosingBefore($query, Carbon $time)
    {
        return $query->where('closing_at', '<=', $time);
    }

    public function scopeClosingAfter($query, Carbon $time)
    {
        return $query->where('closing_at', '>=', $time);
    }

    /*
    * Handles the trailing data error
    */
    public function setPublishedAtAttribute($datetime)
    {
        if (!isset($datetime)) {
            return true;
        }

        try {
            $this->attributes['published_at'] = Carbon::parse($datetime);
        } catch (\Exception $err) {
            $this->attributes['published_at'] = Carbon::createFromFormat('D M d Y H:i:s e+', $datetime); // Thu Nov 15 2012 00:00:00 GMT-0700 (Mountain Standard Time)
        }
    }

    /**
     * This scope puts null fields first (drafts) and then the latest.
     ***/
    public function scopeLatest($query)
    {
        $query->orderBy(\DB::raw('-published_at'), 'asc');
    }
}
