<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetRememberTokenTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function standard_user_created_has_not_got_one_time_token_set()
    {
        $user = factory(App\User::class)->create();

        $this->dontSeeInDatabase('one_time_tokens', [
          'user_id' => $user->id,
        ]);
    }
}
