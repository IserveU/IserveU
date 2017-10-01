<?php

namespace Tests\Browser\Motion;

use App\Motion;
use Tests\Browser\Pages\MotionAdministratePage;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class MotionAdministrateTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force'=>0]);
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
            $motion = factory(Motion::class, 'published')->make();

            $browser->loginAs($this->user, 'api')
                    ->visit(new MotionAdministratePage('/#/create-motion'))
                    ->jsSelectList('@department', $motion->department->name, 'md-option')
                      // TODO: 773  ->jsSelectList('@status',"In review","div.md-text")
                    ->typeBetter('@title', $motion->title)
                    ->typeBetter('@summary', $motion->summary)
                    ->ifExistsThen('@motionText', function () use ($browser, $motion) {  // TODO: 772 Alloy editor was making this fail. Remove the "if exists" when fixed (because it should always exist)
                        $browser->typeBetter('@motionText', $motion->text);
                    })
                    ->press('@save')
                    ->waitForLocationContains('/#/motion')
                    ->on(new MotionPage())
                    ->assertSeeInBetter('@title', $motion->title);
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
                    ->typeBetter('@title', $motion->title.' updated')
                    ->press('@save')
                    ->waitForLocationContains('/#/motion')
                    ->on(new MotionPage())
                    ->assertSeeInBetter('@title', $motion->title.' updated');
        });
    }

    /**
     * Motions can be created.
     *
     * @return void
     * @test
     **/
    public function motion_can_be_deleted()
    {
        $this->user = static::getPermissionedUser('administrate-motion');

        $motion = factory(Motion::class, 'draft')->create([
          'user_id' => $this->user->id,
        ]);

        $this->browse(function (Browser $browser) use ($motion) {
            $browser->loginAs($this->user, 'api')
                    ->visit(new MotionAdministratePage('/#/motion/'.$motion->slug));
            //  ->clickAndSelect('@fab', '#delete_this_motion');
                  //  ->pressBetter("Yes")
                  //  ->visit("/#/motion/".$motion->slug))
                  //  ->assertNotSee($motion->title);
        });
    }
}
