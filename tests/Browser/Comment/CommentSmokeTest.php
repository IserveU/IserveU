<?php

namespace Tests\Browser\Motion;

use App\Comment;
use App\Motion;
use App\User;
use App\Vote;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class CommentSmokeTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force'=>0]);
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

            $browser->loginAs($this->user, 'api')
                ->visit(new MotionPage('/#/motion/'.$this->motion->slug))
                ->clickBetter('@buttonAgree')
                ->assertSeeInBetter('@userCommentTitle', 'Why Did You Agree?')
                ->typeBetter('@userComment', $comment->text)
                ->pressBetter('@userCommentSave')
                ->waitForText($comment->text)
                ->assertSeeInBetter('@commentsAgree', $comment->text)
                ->clickBetter('@buttonDisagree')
                ->clickBetter('@commentListDisagreeButton')
                ->waitForText($comment->text)
                ->assertSeeInBetter('@commentsDisagree', $comment->text);
        });
    }
}
