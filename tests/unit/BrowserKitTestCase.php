<?php

use App\Motion;
use App\User;
use App\Vote;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Tests\CreatesApplication;
use Tests\PolishedTest;

abstract class BrowserKitTestCase extends BaseTestCase
{
    use CreatesApplication, PolishedTest;

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
            static::$votingUser = factory(User::class)->create();

            $votes = factory(Vote::class, 10)->create([
                'user_id' => static::$votingUser->id,
            ]);
            \DB::commit(); //If triggered from a loction that uses database transactions
        }

        return static::$votingUser;
    }
}
