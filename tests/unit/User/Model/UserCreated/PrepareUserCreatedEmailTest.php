<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;

class PrepareUserCreatedEmailTest extends TestCase
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
        $admin = static::getPermissionedUser('show-user');
        $admin->setPreference('authentication.notify.admin.oncreate', 1)->save();

        $user = factory(App\User::class, 'public')->create();

        $message = $this->getLastMessageFor($admin->email);

        $this->assertEquals($message->subject, 'User Created: '.$user->first_name.' '.$user->last_name);
        $this->assertTrue($message->contains($user->email));
        $this->assertTrue($message->contains($user->first_name.' '.$user->last_name));
        $this->assertTrue($message->contains(url("/#/user/$user->slug")));
    }

    /** @test **/
    public function showUser_administrator_does_not_get_new_user_created_email()
    {
        $admin = static::getPermissionedUser('show-user');
        $admin->setPreference('authentication.notify.admin.oncreate', 0)->save();

        $user = factory(App\User::class, 'public')->create();

        $message = $this->getLastMessageFor($admin->email);
        $this->assertNotEquals($message->subject, 'User Created: '.$user->first_name.' '.$user->last_name);
    }
}
