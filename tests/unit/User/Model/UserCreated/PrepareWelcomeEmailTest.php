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
}
