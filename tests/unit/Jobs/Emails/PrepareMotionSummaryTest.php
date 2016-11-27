<?php

use App\Jobs\Emails\PrepareMotionSummary;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;

class PrepareMotionSummaryTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithMail;

    public function setUp()
    {
        parent::setUp();

        $this->mailerInstance = $this->getMailer();
    }

    // Positive Tests

    /** @test */
    public function motion_summary_email_contains_new_motions_based_on_published_at_field()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $motion = factory(App\Motion::class, 'published')->create();

        DB::table('motions')->where(['id' => $motion->id])->update(['created_at' => Carbon::now()->subYears(1)->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->subYears(1)->format('Y-m-d H:i:s')]);

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertTrue($message->contains($motion->title));
        $this->assertEquals($message->subject, 'Summary of Latest Motions');
    }

    /** @test */
    public function motion_summary_email_contains_motions_that_recently_closed()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $motion = factory(App\Motion::class, 'closed')->create();

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertTrue($message->contains($motion->title));
        $this->assertEquals($message->subject, 'Summary of Latest Motions');
    }

    /** @test */
    public function motion_summary_email_contains_motions_that_will_close_soon()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $motion = factory(App\Motion::class, 'published')->create();

        DB::table('motions')->where(['id' => $motion->id])->update(['closing_at' => Carbon::now()->addHours(20)->format('Y-m-d H:i:s')]);

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertTrue($message->contains($motion->title));
        $this->assertEquals($message->subject, 'Summary of Latest Motions');
    }

    /// Negative Tests

    /** @test */
    public function motion_summary_email_does_not_contain_draft_motions()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $draftMotion = factory(App\Motion::class, 'draft')->create();

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertFalse($message->contains($draftMotion->title));
        $this->assertFalse($message->contains($reviewMotion->title));
    }

    /** @test */
    public function motion_summary_email_does_not_contain_review_motions()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $reviewMotion = factory(App\Motion::class, 'published')->create();

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertFalse($message->contains($reviewMotion->title));
    }

    /** @test */
    public function motion_summary_email_does_not_contain_long_closed_motions()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $closedMotion = factory(App\Motion::class, 'closed')->create([
            'closing_at'    => Carbon::now()->subDays(8),
        ]);

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertFalse($message->contains($closedMotion->title));
    }

    /** @test */
    public function motion_summary_email_does_not_contain_long_published_motions()
    {
        $user = factory(App\User::class)->create();
        $user->setPreference('motion.notify.user.summary', 1)->save();

        $motion = factory(App\Motion::class, 'published')->create([
            'closing_at'    => Carbon::now()->addDays(8),
        ]);

        $motion->published_at = Carbon::now()->subDays(8); //Not mass assignable
        $motion->save();

        dispatch(new PrepareMotionSummary());

        $message = $this->getLastMessageFor($user->email);

        $this->assertFalse($message->contains($motion->title));
    }
}
