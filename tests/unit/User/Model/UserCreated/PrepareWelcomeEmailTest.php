<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;

class PrepareWelcomeEmailTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();
        $this->mailerInstance = $this->getMailer();
    }

    /** @test **/
    public function created_users_get_welcome_email()
    {
        $user = factory(App\User::class, 'public')->create();

        $message = $this->getLastMessageFor($user->email);

        $this->assertEquals($message->subject, 'Welcome');
        $this->assertTrue($message->contains('Welcome,'));
    }


    /** @test **/
    public function user_with_no_password_has_set_password_button()
    {
        $user = factory(App\User::class, 'public')->create([
            'password'  =>  ''
        ]);

        $message = $this->getLastMessageFor($user->email);

        $this->assertEquals($message->subject, 'Welcome');
        $this->assertTrue($message->contains($user->remember_token));
        $this->assertTrue($message->contains("Get Started"));

    }


    /** @test **/
    public function user_with_password_do_not_get_set_password_button()
    {
        $user = factory(App\User::class, 'public')->create();

        $message = $this->getLastMessageFor($user->email);

        $this->assertEquals($message->subject, 'Welcome');
        $this->assertFalse($message->contains('Set Password'));

    }
}
