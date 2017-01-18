<?php

include_once 'CommentCache.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentCacheCreateTest extends CommentCache
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setUp();
        Cache::flush();
        $this->signInAsAdmin();
    }

    /** @test  */
    public function creating_comment_clears_filter_cache()
    {
        $this->triggerFilterRoute()
             ->assertNotNull($this->getFilterCache());

        factory(App\Comment::class)->create();

        $this->assertNull($this->getFilterCache());
    }

    /** @test  */
    public function creating_comment_does_not_clear_other_model_cache()
    {
        $this->otherModel = factory(App\Comment::class)->create();

        $this->triggerOtherRoute()
             ->assertNotNull($this->getOtherCache());

        factory(App\Comment::class)->create();

        $this->assertNotNull($this->getOtherCache());
    }
}
