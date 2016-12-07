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

        $publishedMotions = factory(App\Motion::class, 'published', 5)->create();

        foreach ($publishedMotions as $motion) {
            $this->giveMotionComments($motion);
        }

        //Create a published motion
        $publishedMotion = factory(App\Motion::class, 'published')->create([
            'title'     => 'A Published Motion',
            'text'      => '<p>Content of the published motion</p>',
        ]);

        //With attached files
        $file = factory(App\File::class, 'pdf')->create([
            'title' => 'An Attached PDF',
        ]);
        $publishedMotion->files()->save($file);

        $draftMotion = factory(App\Motion::class, 'draft')->create([
            'title' => 'A Draft Motion',
        ]);

        $scheduledMotion = factory(App\Motion::class, 'draft')->create([
            'title' => 'A Scheduled Motion',
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
        $comments = factory(App\Comment::class, 4)->create();

        foreach ($comments as $comment) {
            $comment->vote->motion_id = $motion->id;
            $comment->vote->save();
        }

        //Each commenter likes a random comment
        foreach ($comments as $comment) {
            \App\CommentVote::create([
                'comment_id'    => $comments->random()->id,
                'vote_id'       => $comment->vote_id,
                'position'      => rand(-1, 1),
            ]);
        }
    }
}
