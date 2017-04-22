<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionDatabaseTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    /** @test  */
    public function status_scope_get_motions_with_a_status()
    {
        $motionDraft = factory(App\Motion::class, 'draft')->create();
        $motionReview = factory(App\Motion::class, 'review')->create();
        $motionPublished = factory(App\Motion::class, 'published')->create();
        $motionClosed = factory(App\Motion::class, 'closed')->create();

        $motion = \App\Motion::status('draft')->first();
        $this->assertEquals($motion->status, 'draft');

        $motion = \App\Motion::status('review')->first();
        $this->assertEquals($motion->status, 'review');

        $motion = \App\Motion::status('published')->first();
        $this->assertEquals($motion->status, 'published');

        $motion = \App\Motion::status('closed')->first();
        $this->assertEquals($motion->status, 'closed');
    }

    /** @test  */
    public function status_scope_get_motions_with_many_status()
    {
        $motionDraft = factory(App\Motion::class, 'draft')->create();
        $motionReview = factory(App\Motion::class, 'review')->create();
        $motionPublished = factory(App\Motion::class, 'published')->create();
        $motionClosed = factory(App\Motion::class, 'closed')->create();

        $motion = \App\Motion::status(['draft', 'review', 'published'])->first();
        $this->assertNotEquals($motion->status, 'closed');

        $motion = \App\Motion::status(['review', 'published', 'closed'])->first();
        $this->assertNotEquals($motion->status, 'draft');

        $motion = \App\Motion::status(['published', 'closed'])->first();
        $this->assertNotEquals($motion->status, 'draft');
        $this->assertNotEquals($motion->status, 'review');
    }

    /** @test  */
    public function update_a_draft_motion_details()
    {
        $faker = \Faker\Factory::create();

        $motionDraft = factory(App\Motion::class, 'draft')->create();

        $newDetails = [
            'title'         => $faker->word,
            'summary'       => $faker->sentence,
            'text'          => '<p>'.$faker->sentence.'</p>',
            'closing_at'    => \Carbon\Carbon::now()->addDays(14),
        ];

        $motionDraft->update($newDetails);

        //JSON Field
        unset($newDetails['text']);

        $this->seeInDatabase('motions', $newDetails);
    }

    /** @test  */
    public function update_a_published_motion_details()
    {
        $faker = \Faker\Factory::create();

        $motion = factory(App\Motion::class, 'published')->create();

        $newDetails = [
            'title'         => $faker->word,
            'summary'       => $faker->sentence,
            'text'          => '<p>'.$faker->sentence.'</p>',
            'closing_at'    => \Carbon\Carbon::now()->addDays(14),
        ];

        $motion->update($newDetails);

        //JSON Field
        unset($newDetails['text']);

        $this->seeInDatabase('motions', $newDetails);
    }
}
