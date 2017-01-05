<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $request;
    protected $query;
    protected $filters = [];

    public function __construct(Request $request = null)
    {
        if ($request) {
            $this->filters = $request->all();
        }

        return $this;
    }

    public static function createManually(array $manualFilters)
    {
        $instance = new static();
        $instance->filters = $manualFilters;

        return $instance;
    }

    /* Merge the users filters with the fallbacks */
    public function filters()
    {
        return array_merge($this->defaultsUnlessOverridden, $this->filters);
    }

    public function apply(Builder $query)
    {
        $this->query = $query;

        foreach ($this->filters() as $name => $value) {
            if (method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value], function($value){
                    return ($value !== null && $value !== false && $value !== ''); // don't filter out zeros
                }));
            }
        }

        return $this->query;
    }

    /**
     * Creates a key for the current query filter so that you can cache it and save the results for later.
     *
     * @param string $append lets you append a string when generating the cache key in case you want to save specific version like a cached rendered result and a cached query result
     *
     * @return string a JSON encoded string representing this query filter
     */
    public function cacheKey($append = '')
    {
        $filterKey = '';

        foreach ($this->filters as $key => $value) {
            $filterKey .= $key.json_encode($value);
        }
        $filterKey .= $append;

        return $filterKey;
    }
}
