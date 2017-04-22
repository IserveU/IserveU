<?php

use App\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefaultCommentPermissionTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    protected $route = '/api/comment/';
    protected $class = App\Comment::class;
    protected $table = 'comments';
    protected $alwaysHidden = ['vote_id'];
    protected $defaultFields = [];
    protected $modelToUpdate;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /*****************************************************************
    *
    *                   Deprecated classes
    *
    ******************************************************************/

    /** @test */
    public function comment_index_permissions_working()
    {
        $comment = factory(App\Comment::class)->create();

        $this->filterFieldsGetSee([], 200, $comment->text);
    }

    /** @test */
    public function it_can_see_comments_made_on_the_motion()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->call('GET', '/api/motion/'.$motion->slug.'/comment');

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_create_a_comment()
    {
        $this->signInAsPermissionedUser('create-comment');

        $comment = postComment($this);

        $this->seeInDatabase('comments', [
            'text'  => $comment->text,
        ]);
    }

    /** @test */
    public function it_cannot_create_a_comment()
    {
        $faker = Faker\Factory::create();

        $vote = factory(App\Vote::class)->create();

        $comment = ['text' => 'text for the motion'];

        $response = $this->call('POST', '/api/vote/'.$vote->id.'/comment', $comment);

        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_can_update_comment()
    {
        $this->signInAsPermissionedUser('create-comment');

        $comment = factory(App\Comment::class)->create();
        $comment->vote->user_id = $this->user->id;
        $comment->vote->save();

        // Update comment
        $updatedComment = [
            'text' => 'updated text for the comment',
        ];

        $this->patch('/api/comment/'.$comment->id, $updatedComment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', ['id' => $comment->id, 'text' => $updatedComment['text']]);
    }

    /** @test */
    public function it_cannot_update_others_comment()
    {
        $this->signInAsPermissionedUser('create-comment');

        $comment = factory(App\Comment::class)->create();

        // Update comment
        $updatedComment = [
            'text' => 'updated text for the comment',
        ];

        $this->patch('/api/comment/'.$comment->id, $updatedComment);
        $this->assertResponseStatus(403);
        $this->dontSeeInDatabase('comments', ['id' => $comment->id, 'text' => $updatedComment['text']]);
    }

    /** @test */
    public function it_can_delete_comment()
    {
        $this->signInAsPermissionedUser('create-comment');
        $comment = factory(App\Comment::class)->create();
        $comment->vote->user_id = $this->user->id;
        $comment->vote->save();

        // Delete comment
        $this->delete('/api/comment/'.$comment->id);

        $this->assertResponseOk();
        $this->dontSeeInDatabase('comments', ['id' => $comment->id]);
    }

    /** @test */
    public function it_can_delete_others_comment()
    {
        $this->signInAsPermissionedUser('delete-comment');
        $comment = factory(App\Comment::class)->create();

        // Delete comment
        $this->delete('/api/comment/'.$comment->id);

        $this->assertResponseOk();
        $this->dontSeeInDatabase('comments', ['id' => $comment->id]);
    }

    /** @test */
    public function it_cannot_delete_others_comment()
    {
        $this->signInAsPermissionedUser('create-comment');
        $comment = factory(App\Comment::class)->create();

        // Delete comment
        $this->delete('/api/comment/'.$comment->id);

        $this->assertResponseStatus(403);
        $this->seeInDatabase('comments', ['id' => $comment->id]);
    }

    /** @test */
    public function it_can_see_another_users_comment()
    {
        $comment = factory(App\Comment::class)->create();

        $this->get('/api/comment/'.$comment->id);

        $this->assertResponseStatus(200);

        $this->seeJson(['id' => $comment->id, 'text' => $comment->text]);
    }
}
