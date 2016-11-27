<?php

namespace App\Events\Motion;

use App\Motion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;

class MotionSaving
{
    use InteractsWithSockets, SerializesModels;

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
