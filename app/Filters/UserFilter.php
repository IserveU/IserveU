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

    public function last_name($name = '')
    {
        return $this->query->where('last_name', 'like', "%$name%");
    }

    public function identity_verified($verified = 1)
    {
        return $this->query->where('identity_verified', $verified);
    }

    public function address_verified($verified = 1)
    {
        if ($verified) {
            return $this->query->whereNotNull('address_verified_until');
        } else {
            return $this->query->whereNull('address_verified_until');
        }
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
