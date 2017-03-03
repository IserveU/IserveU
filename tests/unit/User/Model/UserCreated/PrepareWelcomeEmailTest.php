<?php

use App\Notifications\Authentication\Welcome;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use MailThief\Testing\InteractsWithMail;

class PrepareWelcomeEmailTest extends TestCase
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
    public function user_with_no_password_has_set_password_button()
    {
        $this->mailerInstance = $this->getMailer();

        $user = factory(App\User::class, 'public')->create([
            'password'  => '',
        ]);

        $message = $this->getLastMessageFor($user->email);

        $this->assertEquals($message->subject, 'Welcome');
        $this->assertTrue($message->contains($user->remember_token));
        $this->assertTrue($message->contains('Get Started'));
    }

    /** @test **/
    public function user_with_password_do_not_get_set_password_button()
    {
        $this->mailerInstance = $this->getMailer();

        $user = factory(App\User::class, 'public')->create();

        $message = $this->getLastMessageFor($user->email);

        $this->assertEquals($message->subject, 'Welcome');
        $this->assertFalse($message->contains('Set Password'));
    }
}
