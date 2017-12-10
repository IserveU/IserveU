<?php

include_once 'MotionCache.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCacheDeleteTest extends MotionCache
{
    // use DatabaseTransactions;

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
             ->assertNotNull($this->getFilterCache(20, true));

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

    /** @test  ******************/
    public function delete_motion_clears_motioncomments_on_motion()
    {
        $comment = factory(App\Comment::class)->create();

        $this->get('/api/motion/'.$comment->motion->slug.'/comment');

        $this->assertNotNull(Cache::tags(['motion', 'comment'])->get($comment->motion->id));

        $this->delete('/api/motion/'.$comment->motion->slug)
            ->assertResponseStatus(200);

        $this->assertNull(Cache::tags(['motion', 'comment'])->get($comment->motion->id));
    }

    /**
     * Gets the model cache for a model (as opposed to API/croute).
     *
     * @param Model $model
     *
     * @return void
     */
    private function getModelCache(Model $model)
    {
        $slug = $model->slug ?? $model->id;

        return Cache::tags([str_singular($this->table),  str_singular($this->table).'.model'])->get($slug);
    }
}
