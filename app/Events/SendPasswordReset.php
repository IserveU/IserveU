<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class SendPasswordReset extends Event
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($credentials)
    {
        $this->user = User::withEmail($credentials['email'])->first();

        if (!$this->user) {
            abort(403, 'Email address not in database');
        }
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
