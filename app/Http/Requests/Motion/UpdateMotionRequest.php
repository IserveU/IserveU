<?php

namespace App\Http\Requests\Motion;

use App\Http\Requests\Request;
use Auth;

class UpdateMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $motion = $this->route()->parameter('motion');

        if ($motion->status == 'closed') { //Motion has closed/expired
           return false;
        }

        if (!Auth::check()) {
            return false;
        }

        if (Auth::user()->can('administrate-motion')) {
            return true;
        }

        if (!Auth::user()->can('create-motion')) {
            return false;
        }

      //Can't change the closing date if it has been voted on
      if ($this->has('closing_at')) {
          if (!$motion->votes->isEmpty() && ($motion->closing_at['carbon'] != null)) {
              return false;
          }
      }

      //Can't change the status over 1 if not an admin
      if ($this->has('status')) {
          if (!Auth::user()->can('administrate-motion') && ($this->status == 'published' || $this->status == 'closed')) {
              return false;
          }
      }

      //Cant set another user as the creator of a motion if you're just a regular citizen
      if ($this->has('user_id')) {
          if (!Auth::user()->can('administrate-motion') && Auth::user()->id != $this->user_id) {
              return false;
          }
      }

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
            'title'             => 'filled|min:1,title|string',
            'summary'           => 'string',
            'text'              => 'nullable',
            'status'            => 'string|valid_status',
            'content'           => 'reject',
            'department_id'     => 'integer|exists:departments,id',
            'published_at'      => 'reject', //This field is set by the status being changed to status
            'implementation'    => 'string|filled|in:binding,non-binding',
            'closing_at'        => 'date|after:today',
            'user_id'           => 'integer|exists:users,id',
            'rank'              => 'integer',
            'id'                => 'integer',
        ];
    }
}
