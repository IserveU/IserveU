<?php

include_once 'FileApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowFileApiTest extends FileApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_file_test()
    {
        $this->signInAsRole('administrator');

        $file = factory(App\File::class)->create();
        $this->parent->files()->save($file);

        $this->get($this->route.$file->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
              'id',
              'slug',
              'title',
              'description',
              'replacement_id',
              'type',
              'mime',
              'fileable_id',
              'fileable_type',
            ]);
    }
}
