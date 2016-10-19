<?php

use App\Events\User\UserCreated;
use App\Events\User\UserCreating;
use App\Events\User\UserDeleted;
use App\Events\User\UserUpdated;
use App\Events\User\UserUpdating;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelEventsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function check_update_events_file()
    {
        $user = factory(App\User::class, 'verified')->create();
        $this->expectsEvents(UserUpdating::class);
        $this->expectsEvents(UserUpdated::class);
        $this->doesntExpectEvents(UserCreating::class);
        $this->doesntExpectEvents(UserCreated::class);
        $this->doesntExpectEvents(UserDeleted::class);

        $user->first_name = 'Namechange McGee';
        $user->save();
    }
}
