<?php

namespace App\Repositories\Contracts;

interface CachedModel
{


    /**
     * Remove this items cache regardless of it's ID or Slug
     * Not concerned with relations, just what this items has cached
     * Do not nest related caches in here or there may be an infinate loop
     *
     * @return null
     */
    public function flushCache($fromModel = null);

    /**
     * Clears the caches of related models or there relations if needed
     * 
     * @return null
     */
    public function flushRelatedCache($fromModel = null);


}
