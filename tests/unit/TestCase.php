<?php

include_once 'PolishedTest.php';

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use PolishedTest;

    protected $contentToPost;

    public $user;   // (public) Depreciated

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    public static $aNormalMotion;
    public static $votingUser;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        ini_set('memory_limit', '1028M');

        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * To speed up tests so there is one motion that can be used to check
     * things on.
     *
     * @return App\Motion One fully stocked motion
     */
    public function getStaticMotion()
    {
        if (!static::$aNormalMotion) {
            static::$aNormalMotion = aNormalMotion();
            \DB::commit(); //If triggered from a loction that uses database transactions
        }

        return static::$aNormalMotion;
    }

    /**
     * To speed up tests so there is one user that can be used to check votes
     * on.
     *
     * @return App\Motion One fully stocked motion
     */
    public function getVotingUser()
    {
        if (is_null(static::$votingUser)) {
            static::$votingUser = factory(App\User::class)->create();

            $votes = factory(App\Vote::class, 10)->create([
                'user_id'   => static::$votingUser->id,
            ]);
            \DB::commit(); //If triggered from a loction that uses database transactions
        }

        return static::$votingUser;
    }
}
