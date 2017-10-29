<?php

namespace App\Filters;

class CommentFilter extends QueryFilter
{
    //Done unless otherwise specified, very important for security
    protected $defaultsUnlessOverridden = [
        'orderBy' => ['commentRank' => 'desc', 'created_at' => 'desc'],
    ];

    public function createdBefore($date)
    {
        return $this->query->createdBefore($date);
    }

    public function createdAfter($date)
    {
        return $this->query->createdAfter($date);
    }

    /************* ORDERING ****************************************/

    /* desc or asc of closingAt,publisheAt and createdAt*/
    public function orderBy($fieldPairs)
    {
        foreach ($fieldPairs as $field => $direction) {
            switch ($field) {
            case 'commentRank':
                $this->query->orderByCommentRank($direction);

                break;
            default:
              $this->query->orderBy($field, $direction);

          }
        }

        return $this->query;
    }
}
