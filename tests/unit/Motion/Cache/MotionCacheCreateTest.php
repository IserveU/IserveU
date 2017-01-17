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
    public function creating_motion_clears_index_cache()
    {
        $this->motionToUpdate = factory(App\Motion::class)->create();

        $this->get('/api/motion');

        $this->assertNotNull(Cache::tags(['motion', 'motion.filters'])->get(20));

        factory(App\Motion::class)->create();
        $this->assertNull(Cache::tags(['motion', 'motion.filters'])->get(20));
    }

    /** @test  */
    public function creating_motion_does_not_clear_other_query_caches()
    {
        $otherMotion = factory(App\Motion::class)->create();

        $this->get('/api/motion/'.$otherMotion->slug)->assertResponseStatus(200);

        $this->assertNotNull(Cache::tags(['motion', 'motion.model'])->get($otherMotion->slug));

        factory(App\Motion::class)->create();

        $this->assertNotNull(Cache::tags(['motion', 'motion.model'])->get($otherMotion->slug));
    }
}
