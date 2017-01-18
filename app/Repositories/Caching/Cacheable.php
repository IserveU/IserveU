<?php

namespace App\Repositories\Caching;

/**
 *   A reusable status and published trait to manage visibility of montions and users.
 **/
trait Cacheable
{
    public static function bootCacheable()
    {
        static::created(function ($model) {
            $model->flushCache($model);
            $model->flushRelatedCache($model);

            return true;
        });

        static::updated(function ($model) {
            $model->flushCache($model);
            $model->flushRelatedCache($model);

            return true;
        });

        static::deleted(function ($model) {
            $model->flushCache($model);
            $model->flushRelatedCache($model);

            return true;
        });
    }
}
