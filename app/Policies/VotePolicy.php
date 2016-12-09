<?php

namespace App\Policies;

use App\Motion;
use App\Vote;
use Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class VotePolicy
{
    use HandlesAuthorization;

    public function inputsAllowed(array $inputs, Motion $motion, Vote $vote = null)
    {
        if (!Auth::check()) {
            return false;
        }

        if (!Auth::user()->can('create-vote')) {
            return false;
        }

        if (!$motion->exists()) {
            abort(403, 'Motion does not exist');
        }

        if (!$motion->motionOpenForVoting) { //Motion has closed/expired
             abort(403, 'Motion isnt not open for voting');
        }

        if (array_key_exists('user_id', $inputs) && $inputs['user_id'] != Auth::user()->id) {
            abort(403, 'You can only update and create your own vote');
            // Can only vote/alter own
            return false;
        }

        return true;
    }

    //TODO
    public function getVisible(Vote $vote)
    {
        if (Auth::check()) {
            if (Auth::user()->can('show-vote')) { //Admin
                return $user->setVisible(['user_id', 'motion_id', 'position']);
            }

            if ($vote->user_id == Auth::user()->id) { //The person who created this
                return $user->setVisible(['user_id', 'motion_id', 'position']);
            }
        }

        return $user->setVisible(['motion_id', 'position']); //Private user
    }
}
