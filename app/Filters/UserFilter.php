<?php

namespace App\Filters;

class UserFilter extends QueryFilter
{
    //Done unless otherwise specified, very important for security
    protected $defaultsUnlessOverridden = [
        'status'                => ['public'],
        'orderBy'               => ['id' => 'desc'],
    ];

    /* A status or an array of statuses */
    public function status($status = 'public')
    {
        return $this->query->where('status', $status);
    }

    public function first_name($name = '')
    {
        return $this->query->where('first_name', 'like', "%$name%");
    }

    public function identity_verified($verified = 1)
    {
        return $this->query->where('identity_verified', $verified);
    }

    /* desc or asc */
    public function orderBy($fieldPairs)
    {
        foreach ($fieldPairs as $field => $direction) {
            $this->query->orderBy($field, $direction);
        }

        return $this->query;
    }
}
