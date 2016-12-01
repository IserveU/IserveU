<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCreateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test  */
    public function creating_motion_clears_index_cache()
    {
        $this->markTestSkipped('Feature not implemented');

        $this->get('/api/motion');
        //See the cache key exists for a vanilla index pull
        //Create a new draft motion
        //Still see that cache key
        $motion = factory(App\Motion::class)->create();
        //See the cache key has been cleared
    }

    /** @test  */
    public function creating_motion_does_not_clear_other_caches()
    {
        $this->markTestSkipped('Feature not implemented');


        //See the cache key exists for a vanilla index pull on users, etc
        $motion = factory(App\Motion::class)->create();
        //See the cache key has not been been cleared
    }
}
