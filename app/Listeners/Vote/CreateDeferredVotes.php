<?php

namespace App\Listeners\Vote;

use App\Events\MotionCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDeferredVotes
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
     * @param  MotionCreated  $event
     * @return void
     */
    public function handle(MotionCreated $event)
    {
        $motion = $event->motion;
        // DB::enableQueryLog();
        $validUsers = User::validVoter()->get();
        // print_r(DB::getQueryLog());

        $councillors = User::councillor()->get();

        echo $councillors->count();



    }
}
