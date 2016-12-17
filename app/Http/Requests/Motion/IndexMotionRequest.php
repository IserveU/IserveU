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

        //If you're not an admin and haven't set a status, these are the defaults
        if (!$this->has('status')) {
            $this['status'] = ['published', 'closed'];
           // $this->request->add(['status'=>[2,3]]); Didn't work
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
            'rankGreaterThan'        => 'numeric',
            'rankLessThan'           => 'numeric',
            'departmentId'           => 'numeric|exists:departments,id',
            'orderByClosingDate'     => ['regex:(desc|asc)'],
            'orderByCreateDate'      => ['regex:(desc|asc)'],
            'status'                 => 'array',
            'implementation'         => 'array',
            'userId'                 => 'exists:users,id',
            'limit'                  => 'integer',
            'title'                  => 'max:255',
            'page'                   => 'integer',
        ];
    }
}
