<?php

namespace Tests\Browser\Integration\Vote;

use App\Motion;
use App\User;
use App\Vote;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;

class VoteAppearanceTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force' => 0]);
    }

    /**
     * Making sure that votes display correctly
     * The passing status icon should match.
     *
     * @return void
     * @test
     **/
    public function the_passing_status_icon_should_match_the_status()
    {
        $this->passingMotion = factory(Motion::class, 'published')->create();

        factory(Vote::class)->create([
        'motion_id' => $this->passingMotion->id,
        'position'  => 1,
      ]);

        $this->tiedMotion = factory(Motion::class, 'published')->create();

        factory(Vote::class)->create([
        'motion_id' => $this->tiedMotion->id,
        'position'  => 0,
      ]);

        $this->failingMotion = factory(Motion::class, 'published')->create();

        factory(Vote::class)->create([
        'motion_id' => $this->failingMotion->id,
        'position'  => -1,
      ]);

        $this->user = factory(User::class, 'verified')->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
                  ->visit(new MotionPage('/#/motion/'.$this->passingMotion->slug))
                  ->waitForText('Majority agree')
                  ->assertElementAttributeIs('@passingStatusIcon', 'md-svg-src', 'thumb-up')
                  ->visit(new MotionPage('/#/motion/'.$this->tiedMotion->slug))
                  ->waitForText('Majority tie')
                  ->assertElementAttributeIs('@passingStatusIcon', 'md-svg-src', 'thumbs-up-down')
                  ->visit(new MotionPage('/#/motion/'.$this->failingMotion->slug))
                  ->waitForText('Majority disagree')
                  ->assertElementAttributeIs('@passingStatusIcon', 'md-svg-src', 'thumb-down');
        });
    }

    /**
     * A citizen can vote on published motion and see that reflected in status bar.
     *
     * @return void
     * @test
     **/
    public function the_status_bar_shows_the_vote_count_and_changes_as_voting_occurs()
    {
        $this->motion = factory(Motion::class, 'published')->create();

        //Two people for
        factory(Vote::class)->create([
        'motion_id' => $this->motion->id,
        'position'  => 1,
      ]);

        factory(Vote::class)->create([
        'motion_id' => $this->motion->id,
        'position'  => 1,
      ]);

        //One abstain
        factory(Vote::class)->create([
        'motion_id' => $this->motion->id,
        'position'  => 0,
      ]);

        //One against
        factory(Vote::class)->create([
        'motion_id' => $this->motion->id,
        'position'  => -1,
      ]);

        $this->user = static::getPermissionedUser('create-vote');

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
                  ->visit(new MotionPage('/#/motion/'.$this->motion->slug))
                  ->waitFor('@voteStatusbarAgree')
                  ->assertElementAttributeIs('@voteStatusbarAgree', 'aria-label', '2 Agreed')
                  ->assertElementAttributeIs('@voteStatusbarAbstain', 'aria-label', '1 Abstained')
                  ->assertElementAttributeIs('@voteStatusbarDisagree', 'aria-label', '1 Disagreed')
                  ->press('@buttonDisagree')
                  ->waitForText('Majority tie')
                  ->assertElementAttributeIs('@voteStatusbarDisagree', 'aria-label', '2 Disagreed');
        });
    }

    /**
     * Closed motions are closed.
     *
     * @return void
     * @test
     **/
    public function closed_motions_have_disabled_buttons()
    {
        $this->motion = factory(Motion::class, 'closed')->create();

        factory(Vote::class)->create([
        'motion_id' => $this->motion->id,
        'position'  => -1,
      ]);

        $this->user = static::getPermissionedUser('create-vote');

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
                  ->visit(new MotionPage('/#/motion/'.$this->motion->slug))
                  ->waitForText('Closed')
                  ->waitFor('@buttonDisabled')
                  ->press('@buttonDisabled')
                  ->waitForText('Closed for voting')
                  ->assertSeeIn('@passingStatus', 'Majority disagree');
        });
    }
}
