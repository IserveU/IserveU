<?php

namespace App\Listeners\User\Updated;

use App\Events\User\UserUpdated;
use Redis;

class BroadcastToRedis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserUpdated $event
     *
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $model = $event->user;

        $model->load('roles');

        $data = [

        'event' => 'UserWithId'.$model->id.'IsVerified',
        'data'  => [

                'permissions'       => $model->permissions,
                'identity_verified' => $model->identity_verified,

            ],
        ];

        Redis::publish('connection', json_encode($data));
    }
}
