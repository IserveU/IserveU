<?php

namespace App\Filters;

use Auth;

class VoteFilter extends QueryFilter
{
    //Done unless otherwise specified, very important for security
    protected $defaultsUnlessOverridden = [
        'orderBy'          => ['updated_at' => 'desc'],
        'user'             => null,
    ];

    /* A status or an array of statuses */
    public function user($user = null)
    {
        if (!$user) {
            $user = Auth::user()->id;
        }

        return $this->query->user($user);
    }

    /**
     * Needs to check that it is both not null and not before now.
     * People have to reverify their addresses.
     **/
    public function orderBy($fieldPairs)
    {
        foreach ($fieldPairs as $field => $direction) {
            switch ($field) {
              default:
                /* desc or asc of closingAt,publishedAt and createdAt or another other native table field*/
                $this->query->orderBy($field, $direction);
            }
        }

        return $this->query;
    }
}
