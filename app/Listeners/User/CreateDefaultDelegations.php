<?php

namespace App\Listeners\User;

use App\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Delegation;
use App\Department;

class CreateDefaultDelegations
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $departments = Department::all();
        $councillors = User::councillor()->get();

        if($councillors->isEmpty()){
            return true;
        }

        foreach($departments as $department){
            $leastDelegatedToCouncillor = $councillors->sortBy('totalDelegationsTo')->first();
            $newDelegation = new Delegation;
            $newDelegation->department_id       =   $department->id;
            $newDelegation->delegate_from_id    =   $user->id;
            $newDelegation->delegate_to_id      =   $leastDelegatedToCouncillor->id;
            $newDelegation->save();
        }
    }
}
