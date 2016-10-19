<?php

namespace App\Events\Setup;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class Defaults extends Event
{
    use SerializesModels;

    public $adminUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
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
