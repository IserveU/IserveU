<?php

namespace App\Events\Setup;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Defaults extends Event
{
    use SerializesModels;

    public $adminUser;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $adminUser)
    {
        $this->adminUser = $adminUser;
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
