<?php

use App\Comment;
use App\Motion;
use Illuminate\Database\Seeder;

class FakerDataSeeder extends Seeder
{
    /**
     * People who vote on every motion but don't comment.
     *
     * @var array
     */
    public $regularVoters = [];

    public function __construct()
    {
        $this->regularVoters = factory(App\User::class, 'verified', 10)->create();
        foreach ($this->regularVoters as $regularVoter) {
            $regularVoter->addRole('citizen');
        }
    }

    protected $motion = null;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // Default users
        $user = factory(App\User::class, 'unverified')->create([
            'first_name' => 'MrsUnverified',
            'email'      => 'user@iserveu.ca',
            'password'   => 'abcd1234',
        ]);

        $citizen = factory(App\User::class, 'verified')->create([
            'first_name' => 'MrsVerified',
            'email'      => 'citizen@iserveu.ca',
            'password'   => 'abcd1234',
        ]);

        $citizen->addRole('citizen');

        $representative = factory(App\User::class, 'verified')->create([
            'first_name' => 'MrsRepresentative',
            'email'      => 'representative@iserveu.ca',
            'password'   => 'abcd1234',
        ]);

        $representative->addRole('representative');

        $publishedMotions = factory(App\Motion::class, 'published', 20)->create();

        foreach ($publishedMotions as $publishedMotion) {
            $this->for($publishedMotion)
                  ->giveRegularVotes()
                  ->giveCommentsWithCommentVotes('rand');
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

        $this->for($closedMotion)->giveRegularVotes()->giveCommentsWithCommentVotes();

        //Create a published motion
        $topMotion = factory(App\Motion::class, 'published')->create([
            'title'   => 'A Top Motion',
            'summary' => 'The summary of the published top motion',
            'text'    => '<p>Content of the published top motion</p>',
        ]);

        $this->for($topMotion)->giveRegularVotes(1)->giveVotes(5, 1);

        //Create a published motion
        $bottomMotion = factory(App\Motion::class, 'published')->create([
            'title'   => 'A Bottom Motion',
            'summary' => 'The summary of the published bottom motion',
            'text'    => '<p>Content of the published bottom motion</p>',
        ]);

        $this->for($bottomMotion)->giveRegularVotes(-1)->giveVotes(5, -1);

        //Create a comment on motion
        $commentedMotion = factory(App\Motion::class, 'published')->create([
            'title'   => 'A Commented On Motion',
            'summary' => 'A motion which has widely liked and disliked comments, on both sides',
            'text'    => '<p>A motion with widely liked and disliked comments</p>',
        ]);
        $this->for($commentedMotion)->giveRegularVotes()->giveCommentsWithCommentVotes($commentedMotion)->giveVotes(20);

        $topAgreeComment = factory(App\Comment::class)->create([
            'text'    => 'The Top Agree Comment Text',
            'vote_id' => factory(App\Vote::class, 'agree')->create(['motion_id' => $commentedMotion->id])->id,
        ]);
        static::giveCommentVotes($topAgreeComment, 1);

        $bottomAgreeComment = factory(App\Comment::class)->create([
            'text'    => 'The Bottom Agree Comment Text',
            'vote_id' => factory(App\Vote::class, 'agree')->create(['motion_id' => $commentedMotion->id])->id,
        ]);

        static::giveCommentVotes($bottomAgreeComment, -1);
    }

    public function for(Motion $motion)
    {
        $this->motion = $motion;

        return $this;
    }

    /* Returns the new votes */
    public function giveVotes($number = 5, $position = 'rand')
    {
        //Because if it's random. Need to trigger position time
        for ($i = 1; $i <= $number; $i++) {
            //New voters
            $votes = factory(App\Vote::class)->create([
              'motion_id' => $this->motion->id,
              'position'  => static::getPosition($position),
          ]);
        }

        return $this;
    }

    public function giveRegularVotes($position = 'rand')
    {
        //People who vote but don't comment
        foreach ($this->regularVoters as $regularVoter) {
            factory(App\Vote::class)->create([
            'user_id'   => $regularVoter->id,
            'motion_id' => $this->motion->id,
            'position'  => static::getPosition($position),
        ]);
        }

        return $this;
    }

    /**
     * Generate comments for a motion on a certain side of an issue.
     *
     * @param Motion $motion   [description]
     * @param int    $position 1,0,-1 or "rand"
     *
     * @return [type] [description]
     */
    public function giveCommentsWithCommentVotes($position = 'rand')
    {
        $votes = $this->motion->votes()->get();

        foreach ($votes as $vote) {
            $comment = factory(App\Comment::class)->create([
                'vote_id' => $vote->id,
            ]);

            static::giveCommentVotes($comment, $position);
        }

        return $this;
    }

    //Statics

    public static function giveCommentVotes(Comment $comment, $position = 'rand')
    {
        $votesOnSide = \App\Vote::onMotion($comment->motion_slug)->position($comment->vote->position)->get();

        foreach ($votesOnSide as $voteOnSide) {
            \App\CommentVote::create([
                'comment_id' => $comment->id,
                'vote_id'    => $voteOnSide->id,
                'position'   => static::getPosition($position),
            ]);
        }
    }

    public static function getPosition($position = null)
    {
        if (!$position || $position == 'rand') {
            return rand(-1, 1);
        }

        return $position;
    }
}
