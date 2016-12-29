<?php

use Illuminate\Database\Seeder;

class FakerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $publishedMotions = factory(App\Motion::class, 'published', 25)->create();

        foreach ($publishedMotions as $motion) {
            $this->giveMotionComments($motion);
        }

        //Create a published motion
        $publishedMotion = factory(App\Motion::class, 'published')->create([
            'title'         => 'A Published Motion',
            'summary'       => 'The summary of the published motion',
            'text'          => '<p>Content of the published motion</p>',
            'department_id' => 1,
        ]);

        //With attached files
        $file = factory(App\File::class, 'pdf')->create([
            'title' => 'An Attached PDF',
        ]);
        $publishedMotion->files()->save($file);

        $draftMotion = factory(App\Motion::class, 'draft')->create([
            'title' => 'A Draft Motion',
        ]);

        $reviewMotion = factory(App\Motion::class, 'review')->create([
            'title' => 'A Reviewed Motion',
        ]);

        $closedMotion = factory(App\Motion::class, 'closed')->create([
            'title' => 'A Closed Motion',
        ]);

        $this->giveMotionComments($closedMotion);

        $user = factory(App\User::class, 'unverified')->create([
            'email'     => 'user@iserveu.ca',
            'password'  => 'abcd1234',
        ]);

        $citizen = factory(App\User::class, 'verified')->create([
            'email'     => 'citizen@iserveu.ca',
            'password'  => 'abcd1234',
        ]);

        $citizen->addRole('citizen');

        $representative = factory(App\User::class, 'verified')->create([
            'email'     => 'representative@iserveu.ca',
            'password'  => 'abcd1234',
        ]);

        $representative->addRole('representative');
    }

    public function giveMotionComments($motion)
    {
        $votes = factory(App\Vote::class, 4)->create([
            'motion_id' => $motion->id,
        ]);

        foreach ($votes as $vote) {
            factory(App\Comment::class)->create([
                'vote_id' => $vote->id,
            ]);
        }

        //Each commenter likes/dislikes all comments on their side
        foreach ($votes as $vote) {
            $commentsOnSide = \App\Comment::onMotion($vote->motion_id)->position($vote->position)->get();

            foreach ($commentsOnSide as $commentOnSide) {
                \App\CommentVote::create([
                    'comment_id'    => $commentOnSide->id,
                    'vote_id'       => $vote->id,
                    'position'      => rand(-1, 1),
                ]);
            }
        }
    }
}
