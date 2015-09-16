<?php

namespace App\Listeners\Department;

use App\Events\DepartmentCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDepartmentDelegations
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
     * @param  DepartmentCreated  $event
     * @return void
     */
    public function handle(DepartmentCreated $event)
    {
        //
    }
}
