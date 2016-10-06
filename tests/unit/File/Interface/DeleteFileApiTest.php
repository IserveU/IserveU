<?php
include_once('FileApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteFileApiTest extends FileApi
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();


    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES
   
    /** @test  ******************/
    public function delete_file_correct_response(){
        $this->signInAsRole('administrator');

        $file = factory(App\File::class)->create();
        $this->parent->files()->save($file);

        $this->delete($this->route.$file->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
              "id",
              "slug",
              "title",
              "description",
              "replacement_id",
              "type",
              "mime",
              "fileable_id",
              "fileable_type",
            ]);
        
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
