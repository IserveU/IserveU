<?php

namespace App\Observers;

use App\User;
use Setting;

class UserObserver
{
    /**
     * Listen to the User creating event.
     *
     * @param User $user
     *
     * @return void
     */
    public function creating(User $user)
    {
        //
    }

    /**
     * Listen to the User created event.
     *
     * @param User $user
     *
     * @return void
     */
    public function created(User $user)
    {

  //working on replacing this event.
            //event(new UserCreated($user));
            if (!Setting::get('security.verify_citizens')) {
                $user->addUserRoleByName('citizen');
            }

        $this->sendWelcomeEmail($user);
    }

    /**
     * Listen to the User updating event.
     *
     * @param User $user
     *
     * @return void
     */
    public function updating(User $user)
    {
        event(new UserUpdating($user));
    }

    /**
     * Listen to the User updated event.
     *
     * @param User $user
     *
     * @return void
     */
    public function updated(User $user)
    {
        event(new UserUpdated($user));

        $user->load('roles');

        $data = [

            'event' => 'UserWithId'.$user->id.'IsVerified',
            'data'  => [

                    'permissions'       => $user->permissions,
                    'identity_verified' => $user->identity_verified,

                ],
            ];

        Redis::publish('connection', json_encode($data));
    }

    /**
     * Listen to the User saving event.
     *
     * @param User $user
     *
     * @return void
     */
    public function saving(User $user)
    {
        if (!$user->remember_token) {
            $user->remember_token = str_random(99);
        }

        if (!$user->api_token) {
            $user->api_token = str_random(60);
        }

        return true;
    }

    /**
     * Listen to the User saved event.
     *
     * @param User $user
     *
     * @return void
     */
    public function saved(User $user)
    {
        //
    }

    /**
     * Listen to the User deleting event.
     *
     * @param User $user
     *
     * @return void
     */
    public function deleting(User $user)
    {
        //
    }

    /**
     * Listen to the User deleted event.
     *
     * @param User $user
     *
     * @return void
     */
    public function deleted(User $user)
    {
        event(new UserDeleted($user));
    }

/************************************************************** Additional Functions ********************************************************/
}
