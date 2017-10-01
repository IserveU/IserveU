<?php

use App\Notifications\Authentication\IdentityReverification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use MailThief\Testing\InteractsWithMail;

class UserIdentityReverificationTest extends BrowserKitTestCase
{
    use DatabaseTransactions;
    //  use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();
    }

    /* For some reason this breaks the mailer if in setup */
    public function createVerifiedUser()
    {
        $user = factory(App\User::class, 'verified')->create();
        $user->addRole('citizen');

        return $user;
    }

    //POSITIVE TESTS

    /** @test **/
    public function change_first_name_triggers_citizen_reverification()
    {
        $user = $this->createVerifiedUser();
        $user->first_name = 'My New Name';
        $user->save();

        $user->fresh();

        $this->assertEquals($user->identity_verified, 0);
        $this->assertEquals($user->hasRole('citizen'), false);
    }

    /** @test **/
    public function change_last_name_triggers_citizen_reverification()
    {
        $user = $this->createVerifiedUser();
        $user->last_name = 'My Last Name';
        $user->save();

        $user->fresh();

        $this->assertEquals($user->identity_verified, 0);
        $this->assertEquals($user->hasRole('citizen'), false);
    }

    /** @test **/
    public function change_birthdate_triggers_citizen_reverification()
    {
        $user = $this->createVerifiedUser();
        $user->update(['date_of_birth' => \Carbon\Carbon::now()]);

        $this->assertEquals($user->identity_verified, 0);
        $this->assertEquals($user->hasRole('citizen'), false);
    }

    /** @test **/
    public function verified_user_get_reverification_email_of_changed_credentials()
    {
        $user = $this->createVerifiedUser();

        Notification::fake();

        $user->last_name = 'My Last Name';
        $user->save();

        Notification::assertSentTo(
            [$user],
            IdentityReverification::class
        );
    }

    //NEGATIVE TESTS

    /** @test **/
    public function admin_does_not_trigger_identity_reverification()
    {
        Notification::fake();

        $user = $this->createVerifiedUser();

        $this->signInAsPermissionedUser('administrate-user');

        $user->update(['first_name' => 'New Name']);

        $this->assertEquals($user->identity_verified, 1);
        $this->assertEquals($user->hasRole('citizen'), true);

        Notification::assertNotSentTo(
            $user,
            IdentityReverification::class
        );
    }

    /** @test **/
    public function verified_user_does_not_get_reverification_email_if_change_is_not_credentials()
    {
        Notification::fake();

        $user = $this->createVerifiedUser();

        //Mock settings that don't matter
        $user->setPreference('motion.notify.user.summary.on', 1);
        $user->ethnic_origin_id = 1;
        $user->agreement_accepted_date = Carbon::now();
        $user->save();

        $this->assertEquals($user->identity_verified, 1);
        $this->assertEquals($user->hasRole('citizen'), true);

        Notification::assertNotSentTo(
            $user,
            IdentityReverification::class
        );
    }

    /** @test **/
    public function unverified_user_does_not_get_revervification_email_of_changed_profile()
    {
        Notification::fake();
        $user = factory(App\User::class, 'unverified')->create();

        $user->update(['first_name' => 'Mike Westwick']);

        Notification::assertNotSentTo(
            $user,
            IdentityReverification::class
        );
    }
}
