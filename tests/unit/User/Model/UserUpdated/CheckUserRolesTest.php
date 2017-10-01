<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;

class CheckUserRolesTest extends BrowserKitTestCase
{
    use DatabaseTransactions;
    use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();
    }

    //POSITVE TESTS

    /** @test **/
    public function user_with_verified_identity_and_address_will_be_given_citizen_role()
    {
        //create normal user, with no citizen role.
        $user = factory(App\User::class, 'unverified')->create();

        //give user verified identity and verified address, see that their citizenship is added.
        $user->identity_verified = 1;
        $user->address_verified_until = Carbon::now()->addYears(4);
        $user->save();

        $user->load('roles');

        $this->assertTrue($user->hasRole('citizen'));
    }

    //NEGATIVE TESTS

    /** @test **/
    public function citizen_with_unverified_identity_will_have_citizen_role_stripped()
    {
        $user = factory(App\User::class, 'unverified')->create();
        $user->addRole('citizen');
        $user->touch();

        $this->notSeeInDatabase('role_user', ['user_id' => $user->id]);
    }

    /** @test **/
    public function citizen_with_unverified_address_will_have_citizen_role_stripped()
    {
        $user = factory(App\User::class, 'verified')->create();
        $user->address_verified_until = null;
        $user->save();
        $user->addRole('citizen');
        $user->touch();

        $this->notSeeInDatabase('role_user', ['user_id' => $user->id]);
    }

    /** @test **/
    public function citizen_with_expired_address_will_have_citizen_role_stripped()
    {
        $user = factory(App\User::class, 'verified')->create([
            'address_verified_until'    => Carbon::yesterday(),
        ]);
        $user->addRole('citizen');
        $user->touch();

        $this->notSeeInDatabase('role_user', ['user_id' => $user->id]);
    }

    /** @test **/
    public function user_with_preference_will_be_notifed_of_role_change()
    {
        Notification::fake();

        $user = factory(App\User::class, 'verified')->create();
        $user->setPreference('authentication.notify.user.onrolechange.on', 1)->save();
        $user->addRole('citizen');
        $user->touch();

        Notification::assertSentTo(
            $user,
            App\Notifications\Authentication\RoleGranted::class,
            function ($notification, $channels) {
                return $notification->role->name == 'citizen';
            }

        );
    }

    /** @test **/
    public function user_with_no_password_will_not_be_notifed_of_role_change_even_if_they_have_preference_on() //new users created by admin/csv
    {
        $user = factory(App\User::class, 'verified')->create([
            'password'                  => null,
        ]);
        $user->setPreference('authentication.notify.user.onrolechange.on', 1)->save();

        Notification::fake();

        $user->addRole('citizen');
        $user->touch();

        Notification::assertNotSentTo(
            $user,
            App\Notifications\Authentication\RoleGranted::class
        );
    }

    /** @test **/
    public function user_without_preference_will_not_be_notifed_of_role_change()
    {
        $this->mailerInstance = $this->getMailer();

        $user = factory(App\User::class, 'verified')->create([
          'address_verified_until'    => Carbon::yesterday(),
      ]);
        $user->setPreference('authentication.notify.user.onrolechange.on', 0)->save();

        Notification::fake();

        $user->addRole('citizen');
        $user->touch();

        Notification::assertNotSentTo(
            $user,
            App\Notifications\Authentication\RoleGranted::class
        );
    }
}
