<?php

include_once 'CommentPermission.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowCommentPermissionTest extends CommentPermission
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
      'remember_token',
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

    // A public status comment with user's name?
    //
    // A private status comment without

    /** @test */
    public function show_others_public_status_comment_with_permission()
    {
        $comment = $this->createModel('public');

        $this->signInAsPermissionedUser('show-comment');

        $comment->user->status = 'public';
        $comment->push();

        $this->get('/api/comment/'.$comment->id)
            ->seeInResponse([
              $comment->user->skipVisibility()->setVisible($this->publicVisibleUserVariables)->toArray(),
            ]);

            // ->dontSeeInResponse(
            //   array_merge(
            //     $comment->user->skipVisibility()->setVisible([])->setVisible($this->alwaysHiddenUserVariables)->toArray(),
            //     $this->alwaysHiddenUserVariables
            //   )
            // );
    }
}
