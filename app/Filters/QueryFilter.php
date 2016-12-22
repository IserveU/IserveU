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
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }

        return $this->query;
    }

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
