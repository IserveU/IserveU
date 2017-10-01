+<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;
use App\Notifications\Authentication\UserCreated as UserCreatedNotification;

class PrepareUserCreatedEmailTest extends BrowserKitTestCase
{
    use DatabaseTransactions;
    use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();
        $this->mailerInstance = $this->getMailer();
    }

    /** @test **/
    public function showUser_administrator_get_new_user_created_email()
    {
        Notification::fake();

        $admin = static::getPermissionedUser('show-user');
        $admin->setPreference('authentication.notify.admin.oncreate.on', 1)->save();

        $user = factory(App\User::class, 'public')->create();



        Notification::assertSentTo(
            $admin,
            UserCreatedNotification::class,
            function ($notification, $channels) use ($admin, $user) {
                return $notification->user->id == $user->id;
            }
        );


    }

    /** @test **/
    public function showUser_administrator_does_not_get_new_user_created_email_when_preference_off()
    {
        Notification::fake();

        $admin = static::getPermissionedUser('show-user');
        $admin->setPreference('authentication.notify.admin.oncreate.on', 0)->save();

        $user = factory(App\User::class, 'public')->create();


        Notification::assertNotSentTo(
            $admin,
            UserCreatedNotification::class
        );

    }

    /** @test **/
    public function showUser_administrator_does_not_get_a_user_with_no_password()
    {
        Notification::fake();

        $admin = static::getPermissionedUser('show-user');
        $admin->setPreference('authentication.notify.admin.oncreate.on', 1)->save();

        $user = factory(App\User::class, 'public')->create([
          'password' => null,
        ]);

        Notification::assertNotSentTo(
            $admin,
            UserCreatedNotification::class
        );


    }
}
