<?php

namespace Tests\Browser\Integration\Comment;

use App\Comment;
use App\Motion;
use App\User;
use App\Vote;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;

class CommentSmokeTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force' => 0]);
    }

    /**
     * Tests a user voting, commenting and seeing that comment on a motion change
     * as they change their vote position around.
     *
     * @return void
     * @test
     **/
    public function user_can_login_vote_and_comment_on_a_motion()
    {
        $this->user = factory(User::class, 'verified')->create();

        $this->user->addRole('citizen');

        $this->motion = factory(Motion::class, 'published')->create();

        $this->browse(function (Browser $browser) {
            $comment = factory(Comment::class)->make();
            $browser->loginAs($this->user)
                ->visit(new MotionPage('/#/motion/'.$this->motion->slug))
                ->waitFor('@buttonAgree')
                ->click('@buttonAgree')
                ->assertSeeIn('@userCommentTitle', 'Why Did You Agree?')
                ->waitFor('@toast')
                ->waitUntilMissing('@toast') // Prevents clicking on save when in the way
                ->type('@userComment', $comment->text)
                ->press('@userCommentSave')
                ->waitForText($comment->text)
                ->assertSeeIn('@commentsAgree', $comment->text)
                ->click('@buttonDisagree')
                ->click('@commentListDisagreeButton')
                ->waitForText($comment->text)
                ->assertSeeIn('@commentsDisagree', $comment->text);
        });
    }
}
