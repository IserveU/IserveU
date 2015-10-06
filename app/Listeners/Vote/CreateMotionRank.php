<?php

namespace App\Listeners\Vote;

use App\Events\VoteCreated;
use App\Motion;
use App\MotionRank;
use DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Setting;
use Carbon\Carbon;

class CreateMotionRank
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
     * @param  VoteCreated  $event
     * @return void
     */
    public function handle($event)
    {
       
    }
}