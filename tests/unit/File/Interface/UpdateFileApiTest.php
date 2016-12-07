<?php

include_once 'FileApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateFileApiTest extends FileApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signInAsRole('administrator');
    }

    /** @test  ******************/
    public function update_file_with_title()
    {
        $this->updateFieldsGetSee(['title'], 200);
    }

    /** @test  ******************/
    public function update_file_with_description()
    {
        $this->updateFieldsGetSee(['description'], 200);
    }

    /** @test  ******************/
    public function update_file_with_folder()
    {
        $this->updateFieldsGetSee(['folder'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function update_file_with_slug_fails()
    {
        $this->updateContentGetSee([
            'slug'     => 'the-slug',
        ], 400);
    }

    /** @test  ******************/
    public function update_file_with_user_id_fails()
    {
        $this->updateContentGetSee([
            'user_id'     => \Auth::user()->id,
        ], 400);
    }

    /** @test  ******************/
    public function update_file_with_replacement_id_fails()
    {
        $file = factory(App\File::class)->create();
        $this->updateContentGetSee([
            'replacement_id'     => $file->id,
        ], 400);
    }

    /** @test  ******************/
    public function update_file_with_type_fails()
    {
        $this->updateContentGetSee([
            'type'     => 'porkchop',
        ], 400);

        $this->updateContentGetSee([
            'type'     => 'image',
        ], 400);
    }

    /** @test  ******************/
    public function update_file_with_mime_fails()
    {
        $this->updateContentGetSee([
            'mime'     => 'french',
        ], 400);

        $this->updateContentGetSee([
            'mime'     => 'jpg',
        ], 400);
    }

    /** @test  ******************/
    public function update_fileable_type_fails()
    {
        $this->updateContentGetSee([
            'fileable_type'     => "App\Motion",
        ], 400);
    }

    /** @test  ******************/
    public function update_fileable_id_fails()
    {
        $this->updateContentGetSee([
            'fileable_id'     => 99,
        ], 400);
    }

    /** @test  ******************/
    public function update_created_at_fails()
    {
        $this->updateContentGetSee([
            'created_at'     => \Carbon\Carbon::now(),
        ], 400);
    }

    /** @test  ******************/
    public function update_updated_at_fails()
    {
        $this->updateContentGetSee([
            'updated_at'     => \Carbon\Carbon::now(),
        ], 400);
    }
}
