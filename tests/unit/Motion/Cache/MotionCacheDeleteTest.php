<?php

include_once 'MotionCache.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCacheDeleteTest extends MotionCache
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setUp();
        Cache::flush();
        $this->signInAsAdmin();
    }

    /** @test  */
    public function deleting_motion_clears_this_model_cache()
    {
        $this->thisModel = factory($this->class)->create();

        $this->triggerThisRoute()
             ->assertNotNull($this->getThisCache());

        $this->thisModel->delete();

        $this->assertNull($this->getThisCache());
    }

    /** @test  */
    public function deleting_motion_clears_filter_cache()
    {
        $this->thisModel = factory($this->class)->create();

        $this->triggerFilterRoute()
             ->assertNotNull($this->getFilterCache());

        $this->thisModel->delete();

        $this->assertNull($this->getFilterCache());
    }

    /** @test  */
    public function deleting_motion_does_not_clear_other_model_cache()
    {
        $this->thisModel = factory($this->class)->create();
        $this->otherModel = factory($this->class)->create();

        $this->triggerOtherRoute()
             ->assertNotNull($this->getOtherCache());

        $this->thisModel->delete();

        $this->assertNotNull($this->getOtherCache());
    }
}
