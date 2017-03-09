<?php

include_once 'MotionCache.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCacheCreateTest extends MotionCache
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setUp();
        Cache::flush();
        $this->signInAsAdmin();
    }

    /** @test  */
    public function creating_motion_clears_filter_cache()
    {
        $this->triggerFilterRoute()
             ->assertNotNull($this->getFilterCache(20, true));

        factory($this->class)->create();

        $this->assertNull($this->getFilterCache());
    }

    /** @test  */
    public function creating_motion_does_not_clear_other_model_cache()
    {
        $this->otherModel = factory($this->class)->create();

        $this->triggerOtherRoute()
             ->assertNotNull($this->getOtherCache());

        factory($this->class)->create();

        $this->assertNotNull($this->getOtherCache());
    }
}
