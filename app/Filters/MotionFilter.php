<?php

namespace App\Filters;

class MotionFilter extends QueryFilter
{
    //Done unless otherwise specified, very important for security
    protected $defaultsUnlessOverridden = [
        'status'                => ['published', 'closed'],
        'orderBy'               => ['published_at' => 'desc'],
    ];

    /* A status or an array of statuses */
    public function status($status = 'published')
    {
        return $this->query->status($status);
    }

    /* A title or part of a title */
    public function title($title = '')
    {
        return $this->query->where('title', 'like', "%$title%");
    }

    /* Department of motions */
    public function departmentId($department_id = 1)
    {
        return $this->query->where('department_id', $department_id);
    }

    /* Motion creator */
    public function userId($user = 1)
    {
        if (is_numeric($user)) {
            return $this->query->where('user_id', $user);
        }

        return $this->query->where('user_id', $user->id);
    }

    /* Checks that the given record has the implementation field */
    public function implementation($implementation = 'binding')
    {
        if (is_array($implementation)) {
            return $this->query->whereIn('implementation', $implementation);
        }

        return $this->query->where('implementation', $implementation);
    }

    /* check if motion has more votes than the query */
    public function rankGreaterThan($rank = 0)
    {
        return $this->query->whereHas('votes', function ($query) use ($rank) {
            $query->havingRaw('SUM(position) > '.$rank);
        });
    }

    /* check if motion has lesss votes than the query */
    public function rankLessThan($rank = 0)
    {
        return $this->query->whereHas('votes', function ($query) use ($rank) {
            $query->havingRaw('SUM(position) < '.$rank);
        });
    }

    /*Finding queries of all the fields */
    public function allTextFields($string = '')
    {
        return $this->query->where('title', 'like', "%$string%")->orWhere('summary', 'like', "%$string%")
            ->orWhere('implementation', 'like', "%$string%")
            ->orWhere('status', 'like', "%$string%")
            ->orWhere('slug', 'like', "%$string%");
    }

    /************* DATE SCOPES****************************************/

    public function updatedBefore()
    {
        if (!$time) {
            return $this->query;
        }

        $time = new \Carbon\Carbon($time);

        return $this->query->updatedBefore($time);
    }

    public function updatedAfter()
    {
        if (!$time) {
            return $this->query;
        }

        $time = new \Carbon\Carbon($time);

        return $this->query->updatedAfter($time);
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
