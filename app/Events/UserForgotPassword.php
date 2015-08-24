<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserForgotPassword extends Event
{
    use SerializesModels;

    public $user;
    public $credentials;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = User::withEmail($credentials['email'])->first();
        $this->credentials = $credentials;

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
