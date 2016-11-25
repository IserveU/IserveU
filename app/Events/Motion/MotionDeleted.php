<?php

namespace App\Events\Motion;

use App\Motion;
use App\Repositories\SerialisesDeletedModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;

class MotionDeleted
{
    use InteractsWithSockets, SerializesModels, SerialisesDeletedModels {
        SerialisesDeletedModels::getRestoredPropertyValue insteadof SerializesModels;
    }

    public $motion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Motion $motion)
    {
        $this->motion = $motion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
