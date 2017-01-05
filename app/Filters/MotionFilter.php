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

    /* Checks that the given record has the implementation field */
    public function implementation($implementation = 'binding')
    {
        if (is_array($implementation)) {
            return $this->query->whereIn('implementation', $implementation);
        }

        return $this->query->where('implementation', $implementation);
    }

    /*Finding queries of all the fields */
    public function allTextFields($string = '')
    {
        return $this->query->where('title', 'like', "%$string%")->orWhere('summary', 'like', "%$string%")
            ->orWhere('slug', 'like', "%$string%");
    }

    public function userId($id){
        return $this->query->writer($id);
    }

    public function rankLessThan($rank){
        return $this->query->rankLessThan($rank);
    }

    public function rankGreaterThan($rank){
        return $this->query->rankGreaterThan($rank);
    }



    /************* DATE SCOPES****************************************/

    /* desc or asc of closingAt,publisheAt and createdAt*/
    public function orderBy($fieldPairs)
    {
        foreach ($fieldPairs as $field => $direction) {
            $this->query->orderBy($field, $direction);
        }

        return $this->query;
    }
}
