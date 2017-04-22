<?php

include_once 'MotionCache.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCacheUpdateTest extends MotionCache
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setUp();
        Cache::flush();
        $this->signInAsAdmin();
    }

    /** @test  */
    public function updating_motion_clears_this_model_cache()
    {
        $this->thisModel = factory($this->class)->create();

        $this->triggerThisRoute()
             ->assertNotNull($this->getThisCache());

        $this->update($this->thisModel);

        $this->assertNull($this->getThisCache());
    }

    /** @test  */
    public function updating_motion_clears_filter_cache()
    {
        $this->thisModel = factory($this->class)->create();

        $this->triggerFilterRoute()
            ->assertNotNull($this->getFilterCache(20, true));

        $this->update($this->thisModel);

        $this->assertNull($this->getFilterCache());
    }

    /** @test  */
    public function updating_motion_does_not_clear_other_model_cache()
    {
        $this->thisModel = factory($this->class)->create();
        $this->otherModel = factory($this->class)->create();

        $this->triggerOtherRoute()
             ->assertNotNull($this->getOtherCache());

        $this->update($this->thisModel);

        $this->assertNotNull($this->getOtherCache());
    }
}
