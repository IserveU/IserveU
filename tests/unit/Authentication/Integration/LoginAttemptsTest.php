<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginAttemptsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test **/
    public function login_attempts_increment()
    {
        $user = factory(App\User::class)->create();

        $this->seeInDatabase('users', ['id' => $user->id, 'login_attempts' => 0]);

        $this->post('/authenticate', ['email' => $user->email, 'password' => 'wrongpassword1'])
             ->assertResponseStatus(403)
             ->seeInDatabase('users', ['id' => $user->id, 'login_attempts' => 1]);

        $this->post('/authenticate', ['email' => $user->email, 'password' => 'wrongpassword2']);
        $this->seeInDatabase('users', ['id' => $user->id, 'login_attempts' => 2]);
    }
}
