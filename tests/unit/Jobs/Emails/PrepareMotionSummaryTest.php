<?php

use App\Jobs\Emails\PrepareMotionSummary;
use App\Mail\MotionSummary;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;

class PrepareMotionSummaryTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function getUserWithPreferenceTimeForNow(){
      $user = factory(App\User::class)->create();

      $user->setPreference('motion.notify.user.summary.on', 1);

      $hour = Carbon::now()->hour;
      $day  = strtolower(Carbon::now()->format('l'));
      $user->setPreference("motion.notify.user.summary.times.$day", $hour);

      return $user;
    }

    // Positive Tests


    /**
     * Because when Mail::fake is turned on it won't render it, this should at least turn up 500 errors
     * @test
     */
    public function motion_summary_template_can_render()
    {
        $user = $this->getUserWithPreferenceTimeForNow();

        factory(App\Motion::class, 'published')->create([
            'user_id' => $user->id, //Create and see a summary of their own motion to speed up the test
        ]);

        factory(App\Motion::class, 'closed')->create([
            'closing_at'    => Carbon::now()->subHours(12),
            'user_id' => $user->id, //Create and see a summary of their own motion to speed up the test

        ]);

        $motion = factory(App\Motion::class, 'published')->create([
            'user_id' => $user->id, //Create and see a summary of their own motion to speed up the test
        ]);
        //Model does not allow editing of this field
        DB::table('motions')->where(['id' => $motion->id])->update(['closing_at' => Carbon::now()->addHours(20)->format('Y-m-d H:i:s')]);

        dispatch(new PrepareMotionSummary());

    }


    /** @test */
    public function motion_summary_email_contains_new_motions_based_on_published_at_field()
    {
        $user = $this->getUserWithPreferenceTimeForNow();

        $motion = factory(App\Motion::class, 'published')->create([
          'user_id' => $user->id, //Create and see a summary of their own motion to speed up the test
        ]);

        DB::table('motions')->where(['id' => $motion->id])->update(['created_at' => Carbon::now()->subYears(1)->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->subYears(1)->format('Y-m-d H:i:s')]);

        Mail::fake();

        dispatch(new PrepareMotionSummary());

        Mail::assertSentTo([$user], MotionSummary::class, function ($mail) use ($motion) {
            if (!$mail->sections['Latest Launched']->contains($motion)) {
                return false;
            }

            // TODO: Check subject. Currently mailable mocks do not really support this because the var is protected

            // TODO: Check motion URL. Currently mailable mocks do not really support this because you can't get a rendered view (it doesn't actually send)

            return true;
        });
    }

    /** @test */
    public function motion_summary_email_contains_motions_that_recently_closed()
    {
        $user = $this->getUserWithPreferenceTimeForNow();

        $motion = factory(App\Motion::class, 'closed')->create([
            'closing_at'    => Carbon::now()->subHours(12),
        ]);

        Mail::fake();

        dispatch(new PrepareMotionSummary());

        Mail::assertSentTo([$user], MotionSummary::class, function ($mail) use ($motion) {
            if (!$mail->sections['Recently Closed']->contains($motion)) {
                return false;
            }

            //TODO: Check subject. Currently mailable mocks do not really support this because the var is protected

            //TODO: Check motion URL. Currently mailable mocks do not really support this because you can't get a rendered view (it doesn't actually send)

            return true;
        });
    }

    /** @test */
    public function motion_summary_email_contains_motions_that_will_close_soon()
    {
        $user = $this->getUserWithPreferenceTimeForNow();


        $motion = factory(App\Motion::class, 'published')->create();

        DB::table('motions')->where(['id' => $motion->id])->update(['closing_at' => Carbon::now()->addHours(20)->format('Y-m-d H:i:s')]);

        Mail::fake();

        dispatch(new PrepareMotionSummary());

        Mail::assertSentTo([$user], MotionSummary::class, function ($mail) use ($motion) {
            if (!$mail->sections['Closing Soon']->contains($motion)) {
                return false;
            }

            //TODO: Check subject. Currently mailable mocks do not really support this because the var is protected

            //TODO: Check motion URL. Currently mailable mocks do not really support this because you can't get a rendered view (it doesn't actually send)

            return true;
        });
    }

    /// Negative Tests

    /** @test */
    public function motion_summary_email_does_not_contain_draft_motions()
    {
        $user = $this->getUserWithPreferenceTimeForNow();


        $motion = factory(App\Motion::class, 'draft')->create();

        Mail::fake();

        dispatch(new PrepareMotionSummary());

        Mail::send(MotionSummary::class);

        $summaries = Mail::sent(MotionSummary::class, function ($mail) use ($motion) {
            foreach ($mail->sections as $section) {
                if ($section->contains($motion)) {
                    return true;
                }
            }

            return false;
        });

        $this->assertTrue($summaries->isEmpty());
    }

    /** @test */
    public function motion_summary_email_does_not_contain_review_motions()
    {
        $user = $this->getUserWithPreferenceTimeForNow();


        $motion = factory(App\Motion::class, 'review')->create();

        Mail::fake();

        dispatch(new PrepareMotionSummary());

        $summaries = Mail::sent(MotionSummary::class, function ($mail) use ($motion) {
            foreach ($mail->sections as $section) {
                if ($section->contains($motion)) {
                    return true;
                }
            }

            return false;
        });

        $this->assertTrue($summaries->isEmpty());
    }

    /** @test */
    public function motion_summary_email_does_not_contain_long_closed_motions()
    {
        $user = $this->getUserWithPreferenceTimeForNow();


        $motion = factory(App\Motion::class, 'closed')->create([
            'closing_at'    => Carbon::now()->subDays(8),
        ]);

        Mail::fake();

        dispatch(new PrepareMotionSummary());

        $summaries = Mail::sent(MotionSummary::class, function ($mail) use ($motion) {
            foreach ($mail->sections as $section) {
                if ($section->contains($motion)) {
                    return true;
                }
            }

            return false;
        });

        $this->assertTrue($summaries->isEmpty());
    }

    /** @test */
    public function motion_summary_email_does_not_contain_long_published_motions()
    {
        $user = $this->getUserWithPreferenceTimeForNow();


        $motion = factory(App\Motion::class, 'published')->create([
            'closing_at'    => Carbon::now()->addDays(8),
        ]);

        $motion->published_at = Carbon::now()->subDays(8); //Not mass assignable
        $motion->save();

        Mail::fake();

        dispatch(new PrepareMotionSummary());

        $summaries = Mail::sent(MotionSummary::class, function ($mail) use ($motion) {
            foreach ($mail->sections as $section) {
                if ($section->contains($motion)) {
                    return true;
                }
            }

            return false;
        });

        $this->assertTrue($summaries->isEmpty());
    }

    /** @test */
    public function motion_summary_email_does_not_go_to_people_with_the_preference_off()
    {
      $user = $this->getUserWithPreferenceTimeForNow();
      $user->setPreference('motion.notify.user.summary.on', 0);

      $motionB = factory(App\Motion::class, 'closed')->create();
      $motionA = factory(App\Motion::class, 'published')->create();

      Mail::fake();

      dispatch(new PrepareMotionSummary());

      Mail::assertNotSent($user, MotionSummary::class);


    }

    /** @test */
    public function motion_summary_email_does_not_go_to_people_with_a_different_time()
    {
      $user = $this->getUserWithPreferenceTimeForNow();
      $hour = Carbon::now()->hour;
      $day  = strtolower(Carbon::now()->format('l'));
      $user->setPreference("motion.notify.user.summary.times.$day", $hour++);

      $motionB = factory(App\Motion::class, 'closed')->create();
      $motionA = factory(App\Motion::class, 'published')->create();

      Mail::fake();

      dispatch(new PrepareMotionSummary());

      Mail::assertNotSent($user, MotionSummary::class);

    }


        /** @test */
    public function motion_summary_email_does_not_go_to_people_with_a_null_time_for_the_day()
        {
          $user = $this->getUserWithPreferenceTimeForNow();
          $hour = Carbon::now()->hour;
          $day  = strtolower(Carbon::now()->format('l'));
          $user->setPreference("motion.notify.user.summary.times.$day", null);

          $motionB = factory(App\Motion::class, 'closed')->create();
          $motionA = factory(App\Motion::class, 'published')->create();

          Mail::fake();

          dispatch(new PrepareMotionSummary());

          Mail::assertNotSent($user, MotionSummary::class);


        }


}
