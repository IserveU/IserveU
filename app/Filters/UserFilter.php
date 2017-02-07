<?php

namespace App\Filters;

class UserFilter extends QueryFilter
{
    //Done unless otherwise specified, very important for security
    protected $defaultsUnlessOverridden = [
        'status'                => ['public', 'private'],
        'orderBy'               => ['id' => 'desc'],
    ];

    /* A status or an array of statuses */
    public function status($status = 'public')
    {
        return $this->query->status($status);
    }

    public function firstName($name = '')
    {
        return $this->query->where('first_name', 'like', "%$name%");
    }

    public function middleName($name = '')
    {
        return $this->query->where('middle_name', 'like', "%$name%");
    }

    public function lastName($name = '')
    {
        return $this->query->where('last_name', 'like', "%$name%");
    }

    public function allNames($name = '')
    {
        return $this->query->where('first_name', 'like', "%$name%")
                           ->orWhere('middle_name', 'like', "%$name%")
                           ->orWhere('last_name', 'like', "%$name%");
    }

    public function identityVerified($verified = 1)
    {
        return $this->query->where('identity_verified', $verified);
    }

    /**
     * Needs to check that it is both not null and not before now.
     * People have to reverify their addresses.
     **/
    public function addressVerified($verified = 1)
    {
        if ($verified) {
            return $this->query->addressVerified();
        }

        return $this->query->addressVerified();
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
