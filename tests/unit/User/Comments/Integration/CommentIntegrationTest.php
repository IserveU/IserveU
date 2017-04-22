<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentIntegrationTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    protected static $commentingUser;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function user_can_see_comments_after_commenting()
    {
        // sign in
        $user = factory(App\User::class, 'verified')->create();
        $user->addRole('citizen');
        $this->signIn($user);

        $motion = factory(App\Motion::class, 'published')->create();
        $this->post('/api/motion/'.$motion->slug.'/vote', ['position' => 1])->assertResponseStatus(200);
        $this->post('/api/vote/'.$motion->votes()->first()->id.'/comment', ['text' => 'testing testing testing']);
        $this->visit('/api/motion/'.$motion->slug.'/comment')->see('testing testing testing');
    }
}
