<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteUnattachedFilesTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function user_with_updated_government_id_reference_should_have_old_files_deleted()
    {
        $this->markTestSkipped('Not currently taking GIDs, but will need to sort out the delete order on this');

        $governmentID = factory(App\File::class, 'image')->create();
        $user = factory(App\User::class)->create(['government_identification_id' => $governmentID->id]);
        $this->signIn($user);
        $newGovernmentID = factory(App\File::class, 'image')->create();
        $this->patch('/api/user/'.$this->user->slug, ['government_identification_id' => $newGovernmentID->id])->assertResponseStatus(200);
        $this->dontSeeInDatabase('files', ['id' => $governmentID->id]);
    }

    /** @test **/
    public function user_with_updated_avatar_should_have_old_files_deleted()
    {
        $this->markTestSkipped('Not currently setting avatars, but will need to test this');

        $avatarID = factory(App\File::class, 'image')->create();
        $user = factory(App\User::class)->create(['avatar_id' => $avatarID->id]);
        $this->signIn($user);
        $avatarID = factory(App\File::class, 'image')->create();
        $this->patch('/api/user/'.$this->user->slug, ['avatar_id' => $avatarID->id])->assertResponseStatus(200);
        $this->dontSeeInDatabase('files', ['id' => $avatarID->id]);
    }
}
