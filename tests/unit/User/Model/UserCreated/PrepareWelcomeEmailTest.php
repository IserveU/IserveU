<?php

use App\Notifications\Authentication\Welcome;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use MailThief\Testing\InteractsWithMail;

class PrepareWelcomeEmailTest extends BrowserKitTestCase
{
    use DatabaseTransactions;
    use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function created_users_get_welcome_email()
    {
        Notification::fake();

        $user = factory(App\User::class, 'public')->create();

        Notification::assertSentTo(
            $user,
            Welcome::class
        );
    }

    /** @test **/
    public function user_with_no_password_has_one_time_token()
    {
        Notification::fake();

        $this->mailerInstance = $this->getMailer();

        $user = factory(App\User::class, 'public')->create([
            'password'  => '',
        ]);

        Notification::assertSentTo(
            $user,
            Welcome::class,
            function ($notification, $channels) use ($user) {
                return ($notification->token != false);
            }
        );


    }

    /** @test **/
    public function user_with_password_do_not_get_set_password_button()
    {
        Notification::fake();

        $this->mailerInstance = $this->getMailer();

        $user = factory(App\User::class, 'public')->create();

        Notification::assertSentTo(
            $user,
            Welcome::class,
            function ($notification, $channels) use ($user) {
                return ($notification->token == false);
            }
        );

    }
}
