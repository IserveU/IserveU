<?php

namespace App\Listeners\Setup;

use App\Events\Setup\Initialize;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetAdminUser
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
     * @param  Initialize  $event
     * @return void
     */
    public function handle(Initialize $event)
    {
        //
    }
}
