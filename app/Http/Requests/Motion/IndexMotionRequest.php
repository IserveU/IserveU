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
        if (Auth::check() && Auth::user()->can('show-motion')) {
            return true;
        }

        if (array_intersect(['draft', 'review'], $this->input('status'))) {
            if (!Auth::check()) {
                return false;
            }

            //If you want to see unpublshed motions you can only see yours
            //$this->request->add(['user_id'=>Auth::user()->id]);
            $this['user_id'] = Auth::user()->id;
        }

        //Not trying to see an unpublished motion
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'allTextFields'           => 'max:255',
            'rankGreaterThan'         => 'numeric',
            'rankLessThan'            => 'numeric',
            'departmentId'            => 'numeric|exists:departments,id',
            'orderBy.closing_at'      => ['regex:(desc|asc)'],
            'orderBy.published_at'    => ['regex:(desc|asc)'],
            'orderBy.created_at'      => ['regex:(desc|asc)'],
            'status'                  => 'array',
            'implementation'          => 'array',
            'userId'                  => 'exists:users,id',
            'limit'                   => 'integer',
            'title'                   => 'max:255',
            'page'                    => 'integer',
        ];
    }
}
