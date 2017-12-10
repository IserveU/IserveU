<?php

namespace Tests\Browser\Integration\Motion;

use App\Motion;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\JavascriptMenuContainer;
use Tests\Browser\Pages\MotionAdministratePage;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;

class MotionAdministrateTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force' => 0]);
    }

    /**
     * Motions can be created.
     *
     * @return void
     * @test
     **/
    public function motion_can_be_created()
    {
        $this->user = static::getPermissionedUser('create-motion');

        $this->browse(function (Browser $browser) {
            $motion = factory(Motion::class, 'published')->make([
                'department_id' => 8, //Down the list
            ]);

            $browser->loginAs($this->user, 'api')
                ->visit(new MotionAdministratePage('/#/create-motion'))
                ->pause(500)
                ->click('@department')
                ->within(new JavascriptMenuContainer(),
                    function ($browser) use ($motion) {
                        return $browser->selectOption($motion->department->name);
                    }
                )
                ->type('@title', $motion->title)
                ->type('@summary', $motion->summary)
                ->type('@motionText', $motion->text)
                ->press('@save')
                ->waitForLocationContains('/#/motion')
                ->on(new MotionPage())
                ->assertSeeIn('@title', $motion->title);
        });
    }

    /**
     * Motions can be created.
     *
     * @return void
     * @test
     **/
    public function motion_can_be_edited()
    {
        $this->user = static::getPermissionedUser('create-motion');

        $motion = factory(Motion::class, 'draft')->create([
          'user_id' => $this->user->id,
        ]);

        $this->browse(function (Browser $browser) use ($motion) {
            $browser->loginAs($this->user, 'api')
                    ->visit(new MotionAdministratePage('/#/edit-motion/'.$motion->slug))
                    ->pause(500)
                    ->type('@title', $motion->title.' updated')
                    ->press('@save')
                    ->waitForLocationContains('/#/motion')
                    ->on(new MotionPage())
                    ->assertSeeIn('@title', $motion->title.' updated');
        });
    }
}
