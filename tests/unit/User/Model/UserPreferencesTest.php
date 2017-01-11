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
    public function can_create_a_non_existent_user_preference()
    {
        $user = factory(App\User::class)->create();
        $user->createPreference('not.a.preference.here.buddy', 'Not');
        $user->save();

        $user->createPreference('not.a.preference.here.buddy', 'OVER WRITE', true);
        $user->save();
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function can_not_create_an_existing_user_preference()
    {
        $user = factory(App\User::class)->create();
        $user->createPreference('not.a.preference.here.buddy', 'Not');
        $user->save();

        $user->createPreference('not.a.preference.here.buddy', 'OVER WRITE');
        $user->save();
    }

    /** @test **/
    public function can_set_and_get_an_existing_user_preference()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('authentication.notify.admin.oncreate.on', 1);
        $user->save();
        $this->assertEquals($user->getPreference('authentication.notify.admin.oncreate.on'), 1);
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function can_not_set_a_non_existent_user_preference()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('not.a.preference.here.buddy', 'Not');
        $user->save();
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function can_not_set_a_invalid_user_preference()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('authentication.notify.admin.oncreate.on', 'Your Mum');
        $user->save();
    }

    /** @test **/
    public function can_search_by_preference_scope()
    {
        $userB = factory(App\User::class)->create();

        $userA = factory(App\User::class)->create();
        $userA->createPreference('whatever.thing.stuff.yay.ultimate', 'Jump Up, Jump Up and Get Down', true);
        $userA->save();

        $userC = factory(App\User::class)->create();

        $findUser = User::preference('whatever.thing.stuff.yay.ultimate', 'Jump Up, Jump Up and Get Down')->first();

        $this->assertEquals($userA->id, $findUser->id);
    }
}
