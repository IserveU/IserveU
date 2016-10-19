<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class DeleteUserApiTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function delete_user_correct_response()
    {
        $this->signIn();

        $this->delete('/api/user/'.$this->user->id)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
