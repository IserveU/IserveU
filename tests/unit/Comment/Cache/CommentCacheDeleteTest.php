<?php

include_once 'CommentCache.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentCacheDeleteTest extends CommentCache
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setUp();
        Cache::flush();
        $this->signInAsAdmin();
    }

    /** @test  */
    public function deleting_comment_clears_this_model_cache()
    {
        $this->thisModel = factory(App\Comment::class)->create();

        $this->triggerThisRoute()
             ->assertNotNull($this->getThisCache());

        $this->thisModel->delete();

        $this->assertNull($this->getThisCache());
    }

    /** @test  */
    public function deleting_comment_clears_filter_cache()
    {
        $this->thisModel = factory(App\Comment::class)->create();

        $this->triggerFilterRoute()
             ->assertNotNull($this->getFilterCache());

        $this->thisModel->delete();

        $this->assertNull($this->getFilterCache());
    }

    /** @test  */
    public function deleting_comment_does_not_clear_other_model_cache()
    {
        $this->thisModel = factory(App\Comment::class)->create();
        $this->otherModel = factory(App\Comment::class)->create();

        $this->triggerOtherRoute()
             ->assertNotNull($this->getOtherCache());

        $this->thisModel->delete();

        $this->assertNotNull($this->getOtherCache());
    }
}
