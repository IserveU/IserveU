<?php

namespace App\Http\Requests\Motion;

use App\Http\Requests\Request;
use Auth;

class IndexMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //If they are just using the defailts (published/closed)
        if (!$this->has('status')) {
            return true;
        }

        //If they dont want to see hidden motions no problem
        if ($this->has('status') && !array_intersect(['draft', 'review'], $this->input('status'))) {
            return true;
        }

        //If they are admin no problem seeing hidden
        if (Auth::check() && Auth::user()->can('show-motion')) {
            return true;
        }

        //If you are only filtering your own motion drafts/reviews no problem
        if (Auth::check() && $this->has('user_id') && $this->input('user_id') == Auth::user()->id) {
            return true;
        }

        // Trying to see an unpublished motion, and not filtering to see only your own
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'allTextFields'            => 'max:255',
            'rankGreaterThan'          => 'numeric',
            'rankLessThan'             => 'numeric',
            'department_id'            => 'numeric|exists:departments,id',
            'orderBy.closing_at'       => ['regex:(desc|asc)'],
            'orderBy.published_at'     => ['regex:(desc|asc)'],
            'orderBy.created_at'       => ['regex:(desc|asc)'],
            'status'                   => 'array',
            'implementation'           => 'array',
            'user_id'                  => 'exists:users,id',
            'limit'                    => 'integer',
            'title'                    => 'max:255',
            'rank'                     => 'integer',
            'page'                     => 'integer',
        ];
    }
}
