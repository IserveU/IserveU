<?php

namespace Tests\Browser\Integration\HomePage;

use App\Comment;
use App\Motion;
use App\User;
use App\Vote;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class HomePageAppearanceTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setSettings(['site.terms.force' => 0]);
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
            $browser->loginAs($this->user)
                    ->visit(new HomePage())
                    ->waitFor('@topComments')
                    ->waitFor('@topMotions')
                    ->waitForText('A Top Motion')
                    ->assertSeeIn('@topMotions', 'A Top Motion')
                    ->assertSeeIn('@topComments', 'The Top Agree Comment Text')
                    ->assertSeeIn('@yourVotes', "You haven't voted, yet.")
                    ->assertSeeIn('@yourComments', "You haven't commented, yet.");
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
            $browser->loginAs($this->user)
                    ->visit(new HomePage())
                    ->waitFor('@yourVotes')
                    ->waitFor('@yourComments')
                    ->waitForText($this->vote->motion->title) //Put in because line below failed
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
            $browser->visit(new HomePage())
                    ->logout()
                    ->waitForText('Top Comments')
                    ->waitForText('Top Motions')
                    ->waitUntilMissing('@yourVotes')
                    ->waitUntilMissing('@yourComments')
                    ->assertMissing('@yourVotes')
                    ->assertMissing('@yourComments');
        });
    }
}
