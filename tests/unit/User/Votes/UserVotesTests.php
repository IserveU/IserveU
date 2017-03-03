<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class UserVotesTests extends TestCase
{
    use DatabaseTransactions;

    protected $route = '/api/user/'; //extended below
    protected $class = App\Vote::class;
    protected $table = 'votes';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;

    public function setUp()
    {
        parent::setUp();

        static::getVotingUser(); //Will set if not set

        $this->route .= static::$votingUser->id.'/vote';
    }

    public function createModel($owner = null)
    {
        if (!$owner) {
            $owner = factory(\App\User::static)->create();
        }

        $this->modelToUpdate = factory(App\Vote::class)->create([
            'user_id' => $owner->id,
        ]);

        return $this->modelToUpdate;
    }
}
