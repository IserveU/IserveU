<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserPreferencesTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /*****************************************************************
    *
    *                   Privacy functions:
    *
    ******************************************************************/

    /**
     * @test
     */
    public function can_force_set_a_non_existent_user_preferences()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('not.a.preference', 'Not', true);
        $user->save();
    }

    /** @test **/
    public function can_set_and_get_an_existing_user_preference()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('authentication.notify.admin.oncreate', 'Jump Around');
        $user->save();
        $this->assertEquals($user->getPreference('authentication.notify.admin.oncreate'), 'Jump Around');
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function can_not_set_a_non_existent_user_preferences()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('not.a.preference', 'Not');
        $user->save();
    }

    /** @test **/
    public function can_search_by_preference_scope()
    {
        $userB = factory(App\User::class)->create();

        $userA = factory(App\User::class)->create();
        $userA->setPreference('whatever.thing', 'Jump Up, Jump Up and Get Down', true);
        $userA->save();

        $userC = factory(App\User::class)->create();

        $findUser = User::preference('whatever.thing', 'Jump Up, Jump Up and Get Down')->first();

        $this->assertEquals($userA->id, $findUser->id);
    }
}