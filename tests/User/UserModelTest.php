<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Events\User\UserCreated;
use App\Events\User\UserCreating;
use App\Events\User\UserUpdated;
use App\Events\User\UserUpdating;
use App\Events\User\UserDeleted;


class UserModelTest extends TestCase
{
    use DatabaseTransactions;    
    use WithoutMiddleware;

   
    /** @test **/
    public function check_creation_doesnt_do_update(){
        $this->expectsEvents(UserCreating::class);
        $this->expectsEvents(UserCreated::class);
        $this->doesntExpectEvents(UserUpdating::class);
        $this->doesntExpectEvents(UserUpdated::class);
        $this->doesntExpectEvents(UserDeleted::class);

        $user = factory(App\User::class,'verified')->create();
    }
 
    /** @test **/
    public function check_update_events_file(){
        $user = factory(App\User::class,'verified')->create();

        $this->expectsEvents(UserUpdating::class);
        $this->expectsEvents(UserUpdated::class);
        $this->doesntExpectEvents(UserCreating::class);
        $this->doesntExpectEvents(UserCreated::class);
        $this->doesntExpectEvents(UserDeleted::class);

        $user->first_name = "Namechange McGee";
        $user->save();
    }

}