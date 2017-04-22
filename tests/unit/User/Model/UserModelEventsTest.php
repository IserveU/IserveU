<?php

use App\Events\User\UserCreated;
use App\Events\User\UserCreating;
use App\Events\User\UserDeleted;
use App\Events\User\UserDeleting;
use App\Events\User\UserUpdated;
use App\Events\User\UserUpdating;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelEventsTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function check_user_create_events_fire()
    {
        $this->expectsEvents(UserCreated::class);
        $this->expectsEvents(UserCreating::class);
        $user = factory(App\User::class, 'verified')->create();
    }

    /** @test **/
    public function check_user_update_events_fire()
    {
        $user = User::first();  //Because the model events only trigger once in testing
        $this->expectsEvents(UserUpdating::class);
        $this->expectsEvents(UserUpdated::class);
        $user->touch();
    }

    /** @test **/
    public function check_user_delete_events_fire()
    {
        $user = User::first(); //Because the model events only trigger once in testing
        $this->expectsEvents(UserDeleting::class);
        $this->expectsEvents(UserDeleted::class);
        $user->delete();
    }
}
