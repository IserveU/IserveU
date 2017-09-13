<?php

namespace Tests\Browser\Motion;

use App\File;
use App\Motion;
use App\User;
use App\Vote;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class MotionAppearanceTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force'=>0]);
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
                    ->assertSeeInBetter('@title', $this->motion->title)
                    ->assertSeeInBetter('@summary ', $this->motion->summary)
                    ->assertSeeInBetter('@department', $this->motion->department->name)
                    ->assertSeeInBetter('@closing ', $this->motion->closing_at['alpha_date'])
                    ->assertSeeInBetter('@passingStatus ', $this->motion->passing_status)
                    ->assertSeeInBetter('@motionFiles ', $this->file->title)
                    ->assertSeeInBetter('@motionFiles ', $this->file->description)
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

        $this->motion = factory(Motion::class, 'published')->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user, 'api')
            ->visit(new MotionPage('/#/motion/'.$this->motion->slug));

            $sidebarItems = $browser->getAll('sidebar md-list-item');

            $sidebarMotions = [];
            foreach ($sidebarItems as $sidebarItem) {
                if (!empty($sidebarItem->getText())) {
                    $sidebarMotions[] = $sidebarItem;
                }
            }
            $randomMotionA = $sidebarMotions[array_rand($sidebarMotions)];

            $browser->clickBetter($randomMotionA)
                    ->waitForText($randomMotionA->getText());

            $randomMotionB = $sidebarMotions[array_rand($sidebarMotions)];

            $browser->clickBetter($randomMotionB)
                    ->waitForText($randomMotionB->getText());
        });
    }
}
