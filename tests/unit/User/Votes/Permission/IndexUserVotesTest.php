<?php

include_once __DIR__.'/../UserVotesTests.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserVotesTest extends UserVotesTests
{
    use DatabaseTransactions;

    protected $alwaysHiddenUserVariables = [
      'id',
      'email',
      'middle_name',
      'last_name',
      'postal_code',
      'street_name',
      'street_number',
      'unit_number',
      'community_id',
      'status',
      'ethnic_origin_id',
      'date_of_birth',
      'address_verified_until',
      'preferences',
      'login_attempts',
      'locked_until',
      'agreement_accepted_date',
      'api_token',
      'deleted_at',
      'tokens',
      'created_at',
      'updated_at',
      'government_identification_id',
      'avatar_id',
      'phone',
    ];

    protected $publicVisibleUserVariables = [
      'slug',
      'first_name',
      'last_name',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->modelToUpdate = factory(App\Comment::class)->create();

        $this->signIn($this->modelToUpdate->vote->user);

        $this->route = '/api/comment/';
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    // A private status comment without

    /** @test */
    public function cannot_show_public_user_votes_ever()
    {
        $publicUser = static::getPermissionedUser('create-vote', ['status' => 'public']);

        $vote = $this->createModel($publicUser);

        $this->signInAsAdmin();

        $this->get('/api/user/'.$publicUser->slug.'/vote')
            ->assertResponseStatus(200);
    }

    //INCORECT RESPONSES

    /** @test */
    public function cannot_show_private_user_votes_ever()
    {
        $privateUser = static::getPermissionedUser('create-vote', ['status' => 'private']);

        $vote = $this->createModel($privateUser);

        $this->signInAsAdmin();

        $this->get('/api/user/'.$privateUser->slug.'/vote')
            ->assertResponseStatus(403);
    }
}
