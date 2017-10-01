<?php

use App\Jobs\Emails\PrepareAdminSummary;
use App\Notifications\Summary\AdminDailyUserSummary;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;

class PrepareAdminSummaryTest extends BrowserKitTestCase
{
    //use DatabaseTransactions;
    // use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();

        //   $this->mailerInstance = $this->getMailer();
    }

    // Positive Tests

    /** @test */
    public function user_summary_email_contains_new_users()
    {
        Notification::fake();

        $adminUser = static::getPermissionedUser('show-user');
        $adminUser->setPreference('authentication.notify.admin.summary.on', 1)->save();

        $user = factory(App\User::class)->create();

        dispatch_now(new PrepareAdminSummary());

        Notification::assertSentTo(
            $adminUser,
            AdminDailyUserSummary::class,
            function ($notification, $channels) use ($user) {
                return $notification->newUsers->contains($user);
            }
        );

        Notification::assertNotSentTo(
            [$user], AdminDailyUserSummary::class
        );
    }

    /// Negative Tests

    /** @test */
    public function user_summary_email_does_not_send_when_preference_off()
    {
        Notification::fake();

        $adminUser = static::getPermissionedUser('show-user');
        $adminUser->setPreference('authentication.notify.admin.summary.on', 0)->save();

        $user = factory(App\User::class)->create();

        dispatch_now(new PrepareAdminSummary());

        Notification::assertNotSentTo(
            [$adminUser], AdminDailyUserSummary::class
        );
    }

    /** @test */
    public function user_summary_email_does_not_contain_an_administrator_user()
    {
        Notification::fake();

        $adminUser = static::getPermissionedUser('show-user');
        $adminUser->setPreference('authentication.notify.admin.summary.on', 1)->save();

        $siteAdministrator = factory(App\User::class)->create();
        $siteAdministrator->addRole('administrator');

        $regularUser = factory(App\User::class)->create([
          'first_name'  => "O'Dickhead",
        ]);

        dispatch_now(new PrepareAdminSummary());

        Notification::assertSentTo(
            $adminUser,
            AdminDailyUserSummary::class,
            function ($notification, $channels) use ($siteAdministrator, $regularUser) {
                return (!$notification->newUsers->contains($siteAdministrator))
                        && $notification->newUsers->contains($regularUser);
            }
        );
    }
}
