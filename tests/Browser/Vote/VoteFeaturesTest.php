<?php

namespace Tests\Browser\Motion;

use App\Motion;
use App\Vote;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class VoteFeaturesTest extends DuskTestCase
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
    public function voting_by_url_should_work()
    {
        $this->motion = factory(Motion::class, 'published')->create();
        $this->user = static::getPermissionedUser('create-vote');

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
                  ->visit(new MotionPage('/#/motion/'.$this->motion->slug.'/vote/agree'))
                  ->waitForText('Majority agree')
                  ->visit(new MotionPage('/#/motion/'.$this->motion->slug.'/vote/disagree'))
                  ->waitForText('Majority disagree');
        });
    }
}
