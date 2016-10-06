<?php
include_once('FileApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Carbon\Carbon;
use App\File;
class IndexFileApiTest extends FileApi
{
    use DatabaseTransactions;    


    public function setUp()
    {
        parent::setUp();

 
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function file_filter_defaults(){

        $imageFile      = factory(App\File::class,'image')->create();
        $documentFile   = factory(App\File::class,'doc')->create();
        $oldImageFile   = factory(App\File::class,'image')->create(['replacement_id'=>$imageFile->id]);
        $oldestImageFile   = factory(App\File::class,'image')->create(['replacement_id'=>$oldImageFile->id]);


        $this->parent->files()->save($imageFile);
        $this->parent->files()->save($documentFile);
        $this->parent->files()->save($oldImageFile);
        $this->parent->files()->save($oldestImageFile);


        $this->get($this->route)
                ->assertResponseStatus(200)
                ->seeJsonStructure([
                    '*' =>[
                        'previous_version'  => [
                      
                        ]
                    ]
                ]);
    }
 

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
