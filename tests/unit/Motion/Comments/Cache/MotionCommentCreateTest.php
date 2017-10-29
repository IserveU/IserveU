<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCommentCreateTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    /** @test  */
    public function creating_motion_comment_clears_cache()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->get('/api/motion/'.$motion->slug.'/comment');

        $vote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id,
        ]);

        $comment = factory(App\Comment::class)->create([
            'vote_id' => $vote->id,
        ]);

        $this->get('/api/motion/'.$motion->slug.'/comment')->see($comment->text);
    }

    /** @test  */
    public function creating_motion_comment_does_not_clear_other_motions_cache()
    {
        $commentA = factory(App\Comment::class)->create();

        $this->get('/api/motion/'.$commentA->motion->slug.'/comment');
        \DB::table('comments')->where(['id' => $commentA])->update(['text' => 'Updated without cache trigger']);

        $commentB = factory(App\Comment::class)->create();

        $this->get('/api/motion/'.$commentA->motion->slug.'/comment')->dontSee('Updated without cache trigger');
    }
}
