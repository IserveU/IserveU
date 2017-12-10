<?php

namespace Tests\Browser\Integration\Motion;

use App\File;
use App\Motion;
use App\User;
use App\Vote;
use Tests\Browser\Browser;
use Tests\Browser\Components\Sidebar;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;

class MotionAppearanceTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force' => 0]);
    }

    /**
     * Making sure that a motion looks correct
     * The content on a motion page is right.
     *
     * @return void
     * @test
     **/
    public function can_see_correct_parts_of_a_published_motion()
    {
        $this->motion = factory(Motion::class, 'published')->create();

        //With attached files
        $this->file = factory(File::class, 'pdf')->create();
        $this->motion->files()->save($this->file);

        $this->vote = factory(Vote::class)->create([
          'motion_id' => $this->motion->id,
        ]);

        $this->user = factory(User::class, 'verified')->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
                    ->visit(new MotionPage('/#/motion/'.$this->motion->slug))
                    ->waitForText($this->motion->title)
                    ->assertSeeIn('@title', $this->motion->title)
                    ->assertSeeIn('@summary ', $this->motion->summary)
                    ->assertSeeIn('@department', $this->motion->department->name)
                    ->assertSeeIn('@closing ', $this->motion->closing_at['carbon']->format('M j, Y'))
                    ->assertSeeIn('@passingStatus ', $this->motion->passing_status)
                    ->assertSeeIn('@motionFiles ', $this->file->title)
                    ->assertSeeIn('@motionFiles ', $this->file->description)
                    ->assertSeeLink($this->file->title)
                    ->assertElementAttributeIs('@departmentIcon', 'md-svg-icon', $this->motion->department->icon);
        });
    }

    /**
     * Making sure that a motion looks correct
     * The content on a motion page is right.
     *
     * @return void
     * @test
     **/
    public function it_can_navigate_between_motions()
    {
        $this->user = factory(User::class, 'verified')->create();

        $testCase = $this;

        $this->browse(function (Browser $browser) use ($testCase) {
            $browser = $browser->loginAs($this->user, 'api')
            ->visit(new MotionPage())
            ->within(new Sidebar(), function ($browser) {
                $browser->clickRandomMenuItem();
            });

            $textA = $browser->text('@title');

            $browser->within(new Sidebar(), function ($browser) {
                $browser->clickRandomMenuItem();
            });

            $textB = $browser->text('@title');

            if ($textA == $textB) { // In the case randomness choses same item
                $browser->within(new Sidebar(), function ($browser) {
                    $browser->clickRandomMenuItem();
                });
                $textB = $browser->text('@title');
            }

            if ($textA == $textB) { // In the case randomness choses same item
                $browser->within(new Sidebar(), function ($browser) {
                    $browser->clickRandomMenuItem();
                });
                $textB = $browser->text('@title');
            }

            $testCase->assertNotEquals($textA, $textB);
        });
    }
}
