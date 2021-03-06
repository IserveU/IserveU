<?php

namespace App\Events\User;

use App\Events\Event;
use App\Repositories\SerialisesDeletedModels;
use App\User;
use Illuminate\Queue\SerializesModels;

class UserDeleted extends Event
{
    use SerializesModels, SerialisesDeletedModels {
        SerialisesDeletedModels::getRestoredPropertyValue insteadof SerializesModels;
    }
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
