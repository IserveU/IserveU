<?php

namespace Tests\Browser\HomePage;

use App\Comment;
use App\Motion;
use App\User;
use App\Vote;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class HomePageAppearanceTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setSettings(['site.terms.force'=>0]);
    }

    /**
     *   making sure that pages look correct
     *   New user sees correct parts of home page.
     *
     * @return void
     * @test
     **/
    public function can_see_correct_home_page_for_new_user()
    {
        $this->user = factory(User::class, 'verified')->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
                    ->visit(new HomePage())
                    ->waitFor('@topComments')
                    ->waitFor('@topMotions')
                    ->assertSeeInBetter('@topMotions', 'A Top Motion')
                    ->assertSeeInBetter('@topComments', 'The Top Agree Comment Text')
                    ->assertSeeInBetter('@yourVotes', "You haven't voted, yet.")
                    ->assertSeeInBetter('@yourComments', "You haven't commented, yet.");
        });
    }

    /**
     * Making sure that pages look correct
     * Users who have commented can see that in their little boxes.
     *
     * @return void
     * @test
     **/
    public function can_see_correct_home_page_for_commented_user()
    {
        $this->user = factory(User::class, 'verified')->create();

        $this->vote = factory(Vote::class)->create([
          'user_id' => $this->user->id,
        ]);

        $this->comment = factory(Comment::class)->create([
          'vote_id' => $this->vote->id,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
                    ->visit(new HomePage())
                    ->waitFor('@yourVotes')
                    ->waitFor('@yourComments')
                    ->assertSeeIn('@yourVotes', $this->vote->motion->title)
                    ->assertSeeIn('@yourComments', $this->comment->text);
        });
    }

    /**
     * Making sure that pages look correct
     * People who aren't logged in shouldn't see the things
     * related to being a logged in user.
     *
     * @return void
     * @test
     **/
    public function can_see_correct_home_page_for_non_logged_in_user()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->visit(new HomePage())
                    ->waitForText('Top Comments')
                    ->waitForText('Top Motions')
                    ->assertMissing('@yourVotes')
                    ->assertMissing('@yourComments');
        });
    }
}
