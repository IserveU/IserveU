<?php

use App\Jobs\Emails\PrepareMotionSummary;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;

class PrepareMotionSummaryTest extends TestCase
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
    public function motion_summary_email_contains_new_motions()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $motion = factory(App\Motion::class,'published')->create();

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertTrue($message->contains($motion->title));
        $this->assertEquals($message->subject, 'Summary of Latest Motions');
    }

    /// Negative Tests

    /** @test */
    public function user_summary_email_does_not_send_when_preference_off()
    {
        $adminUser = static::getPermissionedUser('show-user');
        $adminUser->setPreference('authentication.notify.admin.summary', 0)->save();

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
        $adminUser->setPreference('authentication.notify.admin.summary', 1)->save();

        $siteAdministrator = factory(App\User::class)->create();
        $siteAdministrator->addRole('administrator');

        $regularUser = factory(App\User::class)->create();

        dispatch(new PrepareAdminSummary());

        $message = $this->getLastMessageFor($adminUser->email);

        $this->assertEquals($message->subject, 'Daily User Summary');
        $this->assertFalse($message->contains($siteAdministrator->first_name.' '.$siteAdministrator->last_name.' ('.$siteAdministrator->email.')'));
        $this->assertTrue($message->contains($regularUser->first_name.' '.$regularUser->last_name.' ('.$regularUser->email.')'));
    }
}
