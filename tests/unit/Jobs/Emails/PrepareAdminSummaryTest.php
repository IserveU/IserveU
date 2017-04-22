<?php

use App\Jobs\Emails\PrepareAdminSummary;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;

class AdminSummaryTest extends BrowserKitTestCase
{
    use DatabaseTransactions;
    use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();

        $this->mailerInstance = $this->getMailer();
    }

    // Positive Tests

    /** @test */
    public function user_summary_email_contains_new_users()
    {
        $adminUser = static::getPermissionedUser('show-user');
        $adminUser->setPreference('authentication.notify.admin.summary.on', 1)->save();

        $user = factory(App\User::class)->create();

        dispatch(new PrepareAdminSummary());

        $message = $this->getLastMessageFor($adminUser->email);

        // This failed once
        $this->assertTrue($message->contains($user->first_name.' '.$user->last_name.' ('.$user->email.')'));
        $this->assertEquals($message->subject, 'Daily User Summary');
    }

    /// Negative Tests

    /** @test */
    public function user_summary_email_does_not_send_when_preference_off()
    {
        $adminUser = static::getPermissionedUser('show-user');
        $adminUser->setPreference('authentication.notify.admin.summary.on', 0)->save();

        $user = factory(App\User::class)->create();

        dispatch(new PrepareAdminSummary());

        $message = $this->getLastMessageFor($adminUser->email);

        $this->assertFalse($message->contains($user->first_name.' '.$user->last_name.' ('.$user->email.')'));
        $this->assertNotEquals($message->subject, 'Daily Admin Summary');
    }

    /** @test */
    public function user_summary_email_does_not_contain_an_administrator_user()
    {
        $adminUser = static::getPermissionedUser('show-user');
        $adminUser->setPreference('authentication.notify.admin.summary.on', 1)->save();

        $siteAdministrator = factory(App\User::class)->create();
        $siteAdministrator->addRole('administrator');

        $regularUser = factory(App\User::class)->create([
          'first_name'  => "O'Dickhead",
        ]);

        dispatch(new PrepareAdminSummary());

        $message = $this->getLastMessageFor($adminUser->email);

        $this->assertEquals($message->subject, 'Daily User Summary');

        $this->assertFalse($message->contains($siteAdministrator->first_name.' '.$siteAdministrator->last_name.' ('.$siteAdministrator->email.')'));
        $this->assertTrue($message->contains($regularUser->first_name.' '.$regularUser->last_name.' ('.$regularUser->email.')'));
    }
}
