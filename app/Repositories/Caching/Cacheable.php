<?php

namespace App\Repositories\Caching;

/**
 *   A reusable status and published trait to manage visibility of montions and users.
 **/
trait Cacheable
{
    public static function bootCachable()
    {
        static::created(function ($model) {
            $this->flushCache();

            return true;
        });

        static::updated(function ($model) {
            $this->flushCache();

            return true;
        });

        static::deleted(function ($model) {
            $this->flushCache();

            return true;
        });
    }
}
