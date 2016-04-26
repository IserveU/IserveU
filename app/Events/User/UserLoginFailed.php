<?php

namespace App\Events\User;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserLoginFailed extends Event
{
    use SerializesModels;

    public $user;
    public $credentials;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($credentials)
    {            
        $this->user = User::withEmail($credentials['email'])->first();
        if(!$this->user){
            abort(403,"Email address not in database");
        }

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